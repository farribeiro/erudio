<?php

namespace SME\CommonsBundle\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use SME\CommonsBundle\Entity\Pessoa;
use SME\CommonsBundle\Entity\PessoaFisica;
use SME\CommonsBundle\Util\DocumentosUtil;

/**
 * Serviço de cadastro único
 * 
 * Permite recuperar, criar e atualizar o cadastro de pessoas físicas de forma padronizada,
 * mantendo os dados consistentes através de validação dos atributos.
 * 
 * Para evitar tentativas de duplicação de cadastro, os métodos create devem ser utilizados
 * para obter um objeto PessoaFisica, mesmo para inserção de novas pessoas.
 * 
 * A idade da pessoa influenciará diretamente na forma de validação, visto que para pessoas 
 * que já atingiram maioridade o documento CPF é um obrigatório, enquanto para crianças é
 * indispensável o preenchimento correto da certidão de nascimento.
 * 
 */
class CadastroUnico
{

    const MAIORIDADE = 18;
    
    private $orm;
    private $validator;
    
    public function __construct (Registry $doctrine, /* ValidatorInterface */ $validator) {
        $this->orm = $doctrine;
        $this->validator = $validator;
    }
    
    public function create (array $restricoes) {
        $pessoa = $this->orm->getRepository('CommonsBundle:PessoaFisica')->findOneBy($restricoes);
        return $pessoa != null ? $pessoa : new PessoaFisica();
    }
    
    public function createById($id) {
        $pessoa = $this->orm->getRepository('CommonsBundle:PessoaFisica')->find($id);
        return $pessoa != null ? $pessoa : new PessoaFisica();
    }
    
    public function createByUser($user) {
        if($user instanceof \SME\IntranetBundle\Entity\PortalUser) {
            return $this->create(array('usuario' => $user));
        }
        else {
            throw new \Exception('Classe de usuário não suportada.');
        }
    }
    
    public function createByCertidaoNascimento($certidaoNascimento) {
        $segmentos = DocumentosUtil::decomporCertidaoNascimento($certidaoNascimento);
        return $this->createByCertidaoNascimentoSegmentada($segmentos['termo'], $segmentos['livro'], $segmentos['folha']);
    }
    
    public function createByCertidaoNascimentoSegmentada($termo, $livro, $folha = '') {
        //formatação de comprimento dos números
        while(\strlen($termo) < DocumentosUtil::CERTIDAO_NASCIMENTO_TERMO_SIZE) { $termo = '0' . $termo; }
        while(\strlen($livro) < DocumentosUtil::CERTIDAO_NASCIMENTO_LIVRO_SIZE) { $livro = '0' . $livro; }
        while(\strlen($folha) < DocumentosUtil::CERTIDAO_NASCIMENTO_FOLHA_SIZE) { $folha = '0' . $folha; }
        
        $qb = $this->orm->getManager()->createQueryBuilder();
        try {
            return $qb->select('p')->from('CommonsBundle:PessoaFisica','p')
                  ->where('p.certidaoNascimento LIKE :certidao')
                  ->setParameter('certidao', '%' . $livro . $folha . $termo . '__')
                  ->getQuery()->getSingleResult();
        } 
        //Caso não exista ninguém com esta certidão, retorna nova instância
        catch(\Doctrine\ORM\NoResultException $ex) {
            return new PessoaFisica();
        }
    }
    
    public function createByCpf($cpf) {
        return $this->create(array('cpfCnpj' => $cpf));
    }
    
    /**
     * @param \SME\CommonsBundle\Entity\Pessoa $pessoa
     */
    public function retain (Pessoa $pessoa) {
        $qb = $this->orm->getManager()->createQueryBuilder();
        
        if($pessoa instanceof PessoaFisica) {
            $cpfValido = DocumentosUtil::validarCpf($pessoa->getCpfCnpj());
            if(!$cpfValido && $pessoa->getCpfCnpj()) {
                throw new DadosInconsistentesException('CPF inválido');
            }
            
            $erros = $this->validator->validate($pessoa, $cpfValido ? array('maior_idade') : array('menor_idade'));
            if(count($erros) > 0) {
                throw new DadosInconsistentesException(print_r($erros, true));
            }
            
            foreach($pessoa->getTelefones() as $telefone) {
                $telefone->setPessoa($pessoa);
            }
            
            $qb = $qb->select('p.id','p.nome','p.ativo','p.cpfCnpj','p.certidaoNascimento')->from('CommonsBundle:PessoaFisica','p');
            if($cpfValido) {
                $qb = $qb->where($qb->expr()->eq('p.cpfCnpj', $pessoa->getCpfCnpj()));
            } else {
                $qb = $qb->where('p.certidaoNascimento LIKE :certidao')
                         ->setParameter('certidao', '%' . $pessoa->getLivroCertidaoNascimento() . $pessoa->getFolhaCertidaoNascimento() . $pessoa->getTermoCertidaoNascimento() . '__');
            }
            $pessoas = $qb->getQuery()->getResult();
            
            if(count($pessoas) === 0) {
                $this->orm->getManager()->persist($pessoa);
            } elseif(count($pessoas) === 1 && $this->orm->getManager()->contains($pessoa)) {
                $this->orm->getManager()->merge($pessoa);
            } else {
                throw new DuplicidadeException(
                        $cpfValido 
                        ? 'Já existe outra pessoa cadastrada com este CPF.' 
                        : 'Já existe outra pessoa cadastrada com esta certidão de nascimento.'); 
            }
            $this->orm->getManager()->flush();
        }
        else {
            throw new \Exception('Somente o cadastro de pessoa física está disponível atualmente.');
        }
        return $pessoa;
    }
    
}
