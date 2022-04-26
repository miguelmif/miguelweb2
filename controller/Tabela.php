<?php
class Tabela
{
  private $message = "";
  public function __construct(){
    Transaction::open();
  }
  public function controller()
  {
      Transaction::get();
      $estoque = new Crud("estoque");
      $resultado = $estoque->select();
      $tabela = new Template("view/tabela.html");
      if (is_array($resultado)) {
        $tabela->set("linha", $resultado);
        $this->message = $tabela->saida();
      }
  }
  
  public function getMessage()
  {
    return $this->message;
  }
  public function __destruct(){
    Transaction::close();
  }
}