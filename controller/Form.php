<?php
class Form
{
  private $message = ""; 
  private $error = "";
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
    if(isset($_POST['produto']) && isset($_POST['preco']) && isset($_POST['quantidade'])) {
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
        $this->message = $estoque->getMessage();
        $this->error = $estoque->getError();
      } catch (Exception $e){
        $this->message = $e->getMessage();
        $this->error = true;
      }
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
        if (!$estoque->getError()) {
          $form = new Template("view/form.html");
          foreach ($resultado[0] as $cod => $valor) {
            $form->set($cod, $valor);
          }
          $this->message = $form->saida();
        } else {
          $this->message = $estoque->getMessage();
          $this->error = true;
        }
      } catch (Exception $e) {
        $this->message = $e->getMessage();
        $this->error = true;
      }
    }
  }
  public function getMessage()
  {
    if (is_string($this->error)) {
      return $this->message;
    } else {
      $msg = new Template("view/msg.html");
      if ($this->error) {
        $msg->set("cor", "danger");
      } else {
        $msg->set("cor", "success");
      }
      $msg->set("msg", $this->message);
      $msg->set("uri", "?class=Tabela");
      return $msg->saida();
    }
  }
  public function __destruct()
  {
    Transaction::close();
  }
}