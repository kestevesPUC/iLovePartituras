<?php namespace App\Models\Helpers;

use App\Models\DefaultModel;

class Email extends DefaultModel
{
    /**
     * @var String diretorio onde se encontra o template do E-mail
     */
    public $template;

    /**
     * @var String caminho onde se encontra a logo
     */
    public $path;

    /**
     * @var String largura da logo
     */
    public $width;

    /**
     * @var String Altura da logo
     */
    public $height;

    /**
     * @var Array Todos os parametros que serão enviado por E-mail
     */
    public $parameters;

    /**
     * @var String nome do sistema
     */
    public $systemName;

    /**
     * @var Array enviar para : email ,nome
     */
    public $emails;

    /**
     * @var Array copia para : email ,nome
     */
    public $bccs;

    /**
     * @var String titulo do e-mail
     */
    public $title;

    /**
     * @var Array arquivo
     */
    public $files;
}
