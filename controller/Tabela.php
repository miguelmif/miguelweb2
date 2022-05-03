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
  public function remover()
  {
    if (isset($_GET["id"])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET["id"]);
        $estoque = new Crud("estoque");
        $estoque->delete("id=$id");
      } catch (Exception $e) {
        echo $e->getMessage();
      }
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