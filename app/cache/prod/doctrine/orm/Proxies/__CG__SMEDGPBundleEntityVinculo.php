<?php

namespace Proxies\__CG__\SME\DGPBundle\Entity;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class Vinculo extends \SME\DGPBundle\Entity\Vinculo implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'id', 'numeroControle', 'servidor', 'matricula', 'dataInicio', 'dataTermino', 'cargo', 'vinculoOriginal', 'tipoVinculo', 'cargaHoraria', 'numeroContaBancaria', 'agenciaContaBancaria', 'bancoContaBancaria', 'quadroEspecial', 'gratificacao', 'lotacaoSecretaria', 'codigoDepartamento', 'codigoSetor', 'dataCadastro', 'ativo', 'portaria', 'edicaoJornalNomeacao', 'inscricaoVinculacao', 'convocacaoVinculacao', 'alocacoes', 'ciGeral', 'validado', 'observacao');
        }

        return array('__isInitialized__', 'id', 'numeroControle', 'servidor', 'matricula', 'dataInicio', 'dataTermino', 'cargo', 'vinculoOriginal', 'tipoVinculo', 'cargaHoraria', 'numeroContaBancaria', 'agenciaContaBancaria', 'bancoContaBancaria', 'quadroEspecial', 'gratificacao', 'lotacaoSecretaria', 'codigoDepartamento', 'codigoSetor', 'dataCadastro', 'ativo', 'portaria', 'edicaoJornalNomeacao', 'inscricaoVinculacao', 'convocacaoVinculacao', 'alocacoes', 'ciGeral', 'validado', 'observacao');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (Vinculo $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function getDescricao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDescricao', array());

        return parent::getDescricao();
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function getServidor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getServidor', array());

        return parent::getServidor();
    }

    /**
     * {@inheritDoc}
     */
    public function setServidor(\SME\CommonsBundle\Entity\PessoaFisica $servidor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setServidor', array($servidor));

        return parent::setServidor($servidor);
    }

    /**
     * {@inheritDoc}
     */
    public function getMatricula()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getMatricula', array());

        return parent::getMatricula();
    }

    /**
     * {@inheritDoc}
     */
    public function setMatricula($matricula)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setMatricula', array($matricula));

        return parent::setMatricula($matricula);
    }

    /**
     * {@inheritDoc}
     */
    public function getDataInicio()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDataInicio', array());

        return parent::getDataInicio();
    }

    /**
     * {@inheritDoc}
     */
    public function setDataInicio(\DateTime $dataInicio = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDataInicio', array($dataInicio));

        return parent::setDataInicio($dataInicio);
    }

    /**
     * {@inheritDoc}
     */
    public function getDataNomeacao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDataNomeacao', array());

        return parent::getDataNomeacao();
    }

    /**
     * {@inheritDoc}
     */
    public function setDataNomeacao(\DateTime $dataNomeacao)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDataNomeacao', array($dataNomeacao));

        return parent::setDataNomeacao($dataNomeacao);
    }

    /**
     * {@inheritDoc}
     */
    public function getDataTermino()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDataTermino', array());

        return parent::getDataTermino();
    }

    /**
     * {@inheritDoc}
     */
    public function setDataTermino($dataTermino)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDataTermino', array($dataTermino));

        return parent::setDataTermino($dataTermino);
    }

    /**
     * {@inheritDoc}
     */
    public function getDataPosse()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDataPosse', array());

        return parent::getDataPosse();
    }

    /**
     * {@inheritDoc}
     */
    public function setDataPosse(\DateTime $dataPosse)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDataPosse', array($dataPosse));

        return parent::setDataPosse($dataPosse);
    }

    /**
     * {@inheritDoc}
     */
    public function getCargo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCargo', array());

        return parent::getCargo();
    }

    /**
     * {@inheritDoc}
     */
    public function setCargo(\SME\DGPBundle\Entity\Cargo $cargo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCargo', array($cargo));

        return parent::setCargo($cargo);
    }

    /**
     * {@inheritDoc}
     */
    public function getVinculoOriginal()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVinculoOriginal', array());

        return parent::getVinculoOriginal();
    }

    /**
     * {@inheritDoc}
     */
    public function getCargoOrigem()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCargoOrigem', array());

        return parent::getCargoOrigem();
    }

    /**
     * {@inheritDoc}
     */
    public function setVinculoOriginal(\SME\DGPBundle\Entity\Vinculo $vinculoOriginal = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVinculoOriginal', array($vinculoOriginal));

        return parent::setVinculoOriginal($vinculoOriginal);
    }

    /**
     * {@inheritDoc}
     */
    public function getTipoVinculo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTipoVinculo', array());

        return parent::getTipoVinculo();
    }

    /**
     * {@inheritDoc}
     */
    public function setTipoVinculo(\SME\DGPBundle\Entity\TipoVinculo $tipoVinculo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTipoVinculo', array($tipoVinculo));

        return parent::setTipoVinculo($tipoVinculo);
    }

    /**
     * {@inheritDoc}
     */
    public function getCargaHoraria()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCargaHoraria', array());

        return parent::getCargaHoraria();
    }

    /**
     * {@inheritDoc}
     */
    public function setCargaHoraria($cargaHoraria)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCargaHoraria', array($cargaHoraria));

        return parent::setCargaHoraria($cargaHoraria);
    }

    /**
     * {@inheritDoc}
     */
    public function getNumeroContaBancaria()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNumeroContaBancaria', array());

        return parent::getNumeroContaBancaria();
    }

    /**
     * {@inheritDoc}
     */
    public function setNumeroContaBancaria($numeroContaBancaria)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNumeroContaBancaria', array($numeroContaBancaria));

        return parent::setNumeroContaBancaria($numeroContaBancaria);
    }

    /**
     * {@inheritDoc}
     */
    public function getAgenciaContaBancaria()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAgenciaContaBancaria', array());

        return parent::getAgenciaContaBancaria();
    }

    /**
     * {@inheritDoc}
     */
    public function setAgenciaContaBancaria($agenciaContaBancaria)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAgenciaContaBancaria', array($agenciaContaBancaria));

        return parent::setAgenciaContaBancaria($agenciaContaBancaria);
    }

    /**
     * {@inheritDoc}
     */
    public function getBancoContaBancaria()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBancoContaBancaria', array());

        return parent::getBancoContaBancaria();
    }

    /**
     * {@inheritDoc}
     */
    public function setBancoContaBancaria(\SME\DGPBundle\Entity\Banco $bancoContaBancaria = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBancoContaBancaria', array($bancoContaBancaria));

        return parent::setBancoContaBancaria($bancoContaBancaria);
    }

    /**
     * {@inheritDoc}
     */
    public function getQuadroEspecial()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getQuadroEspecial', array());

        return parent::getQuadroEspecial();
    }

    /**
     * {@inheritDoc}
     */
    public function setQuadroEspecial($quadroEspecial)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setQuadroEspecial', array($quadroEspecial));

        return parent::setQuadroEspecial($quadroEspecial);
    }

    /**
     * {@inheritDoc}
     */
    public function getGratificacao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getGratificacao', array());

        return parent::getGratificacao();
    }

    /**
     * {@inheritDoc}
     */
    public function setGratificacao($gratificacao)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setGratificacao', array($gratificacao));

        return parent::setGratificacao($gratificacao);
    }

    /**
     * {@inheritDoc}
     */
    public function getLotacaoSecretaria()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getLotacaoSecretaria', array());

        return parent::getLotacaoSecretaria();
    }

    /**
     * {@inheritDoc}
     */
    public function setLotacaoSecretaria($lotacaoSecretaria)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setLotacaoSecretaria', array($lotacaoSecretaria));

        return parent::setLotacaoSecretaria($lotacaoSecretaria);
    }

    /**
     * {@inheritDoc}
     */
    public function getCodigoDepartamento()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCodigoDepartamento', array());

        return parent::getCodigoDepartamento();
    }

    /**
     * {@inheritDoc}
     */
    public function setCodigoDepartamento($codigoDepartamento)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCodigoDepartamento', array($codigoDepartamento));

        return parent::setCodigoDepartamento($codigoDepartamento);
    }

    /**
     * {@inheritDoc}
     */
    public function getCodigoSetor()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCodigoSetor', array());

        return parent::getCodigoSetor();
    }

    /**
     * {@inheritDoc}
     */
    public function setCodigoSetor($codigoSetor)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCodigoSetor', array($codigoSetor));

        return parent::setCodigoSetor($codigoSetor);
    }

    /**
     * {@inheritDoc}
     */
    public function getDataCadastro()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDataCadastro', array());

        return parent::getDataCadastro();
    }

    /**
     * {@inheritDoc}
     */
    public function setDataCadastro(\DateTime $dataCadastro = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDataCadastro', array($dataCadastro));

        return parent::setDataCadastro($dataCadastro);
    }

    /**
     * {@inheritDoc}
     */
    public function getAtivo()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAtivo', array());

        return parent::getAtivo();
    }

    /**
     * {@inheritDoc}
     */
    public function setAtivo($ativo)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAtivo', array($ativo));

        return parent::setAtivo($ativo);
    }

    /**
     * {@inheritDoc}
     */
    public function getPortaria()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPortaria', array());

        return parent::getPortaria();
    }

    /**
     * {@inheritDoc}
     */
    public function setPortaria($portaria)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPortaria', array($portaria));

        return parent::setPortaria($portaria);
    }

    /**
     * {@inheritDoc}
     */
    public function getEdicaoJornalNomeacao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEdicaoJornalNomeacao', array());

        return parent::getEdicaoJornalNomeacao();
    }

    /**
     * {@inheritDoc}
     */
    public function setEdicaoJornalNomeacao($edicaoJornalNomeacao)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEdicaoJornalNomeacao', array($edicaoJornalNomeacao));

        return parent::setEdicaoJornalNomeacao($edicaoJornalNomeacao);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlocacoes()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAlocacoes', array());

        return parent::getAlocacoes();
    }

    /**
     * {@inheritDoc}
     */
    public function setAlocacoes($alocacoes)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setAlocacoes', array($alocacoes));

        return parent::setAlocacoes($alocacoes);
    }

    /**
     * {@inheritDoc}
     */
    public function getAlocacoesOriginais()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAlocacoesOriginais', array());

        return parent::getAlocacoesOriginais();
    }

    /**
     * {@inheritDoc}
     */
    public function getValidado()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValidado', array());

        return parent::getValidado();
    }

    /**
     * {@inheritDoc}
     */
    public function setValidado($validado)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValidado', array($validado));

        return parent::setValidado($validado);
    }

    /**
     * {@inheritDoc}
     */
    public function getProcessoAdmissao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getProcessoAdmissao', array());

        return parent::getProcessoAdmissao();
    }

    /**
     * {@inheritDoc}
     */
    public function getInscricaoVinculacao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getInscricaoVinculacao', array());

        return parent::getInscricaoVinculacao();
    }

    /**
     * {@inheritDoc}
     */
    public function setInscricaoVinculacao(\SME\DGPContratacaoBundle\Entity\Inscricao $inscricaoVinculacao = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setInscricaoVinculacao', array($inscricaoVinculacao));

        return parent::setInscricaoVinculacao($inscricaoVinculacao);
    }

    /**
     * {@inheritDoc}
     */
    public function getConvocacaoVinculacao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getConvocacaoVinculacao', array());

        return parent::getConvocacaoVinculacao();
    }

    /**
     * {@inheritDoc}
     */
    public function setConvocacaoVinculacao(\SME\DGPContratacaoBundle\Entity\Convocacao $convocacaoVinculacao = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setConvocacaoVinculacao', array($convocacaoVinculacao));

        return parent::setConvocacaoVinculacao($convocacaoVinculacao);
    }

    /**
     * {@inheritDoc}
     */
    public function getCiGeral()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCiGeral', array());

        return parent::getCiGeral();
    }

    /**
     * {@inheritDoc}
     */
    public function setCiGeral($ciGeral)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCiGeral', array($ciGeral));

        return parent::setCiGeral($ciGeral);
    }

    /**
     * {@inheritDoc}
     */
    public function getNumeroControle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNumeroControle', array());

        return parent::getNumeroControle();
    }

    /**
     * {@inheritDoc}
     */
    public function setNumeroControle($numeroControle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNumeroControle', array($numeroControle));

        return parent::setNumeroControle($numeroControle);
    }

    /**
     * {@inheritDoc}
     */
    public function getObservacao()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getObservacao', array());

        return parent::getObservacao();
    }

    /**
     * {@inheritDoc}
     */
    public function setObservacao($observacao)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setObservacao', array($observacao));

        return parent::setObservacao($observacao);
    }

    /**
     * {@inheritDoc}
     */
    public function getOpcaoLei()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getOpcaoLei', array());

        return parent::getOpcaoLei();
    }

}
