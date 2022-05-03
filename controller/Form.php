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
    $form->set("id","");
    $form->set("produto","");
    $form->set("preco","");
    $form->set("quantidade","");
    $this->message = $form->saida();
  }
  public function salvar()
  {
    if(isset($_POST['produto']) && isset($_POST['preco']) && isset($_POST['quantidade']))
    try {
      $conexao = Transaction::get();
      $estoque = new Crud('estoque');
      $produto = $conexao->quote($_POST['produto']);
      $preco = $conexao->quote($_POST['preco']);
      $quantidade = $conexao->quote($_POST['quantidade']);
      if (empty($_POST["id"])) {
        $estoque->insert("produto,preco,quantidade", "$produto,$preco,$quantidade");
      } else {
        $id = $conexao->quote($_POST['id']);
        $estoque->update("produto=$produto,preco=$preco,quantidade=$quantidade", "id=$id");
      }
    } catch (Exception $e){
      echo $e->getMessage();
    }
  }
  public function editar()
  {
    if (isset($_GET['id'])) {
      try {
        $conexao = Transaction::get();
        $id = $conexao->quote($_GET['id']);
        $estoque = new Crud('estoque');
        $resultado = $estoque->select("*", "id=$id");
        $form = new Template("view/form.html");
        foreach ($resultado[0] as $cod => $valor){
          $form->set($cod, $valor);
        }
        $this->message = $form->saida();
      } catch (Exception $e) {
        echo $e->getMessage();
      }
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