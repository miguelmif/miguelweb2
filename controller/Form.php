<?php
class Form
{
  private $message = "";
  public function __construct()
  {
    Transaction::open();
  }
  public function controller()
  {
    $form = new Template("view/form.html");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    print_r($_POST);
    if(isset($_POST['produto']) && isset($_POST['preco']) && isset($_POST['quantidade']))
    try {
      $conexao = Transaction::get();
      $estoque = new Crud('estoque');
      $produto = $conexao->quote($_POST['produto']);
      $preco = $conexao->quote($_POST['preco']);
      $quantidade = $conexao->quote($_POST['quantidade']);
      $resultado = $estoque->insert("produto,preco,quantidade", "$produto,$preco,$quantidade");
    } catch (Exception $e){
      echo $e->getMessage();
    }
  }
  public function getMessage()
  {
    return $this->message;
  }
  public function __destruct()
  {
    Transaction::close();
  }
}