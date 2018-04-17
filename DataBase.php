<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class DataBase{
    public $dsn;
    public $usr;
    public $pwd;
    public $opts;
    public $db;
    public $stt;
    
   function __construct($dsn, $usr, $pwd) {
       $this->dsn = $dsn;
       $this->usr = $usr;
       $this->pwd = $pwd;
       $this->opts = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ); 
   }
   
   // DBに接続する
   function connect(){
        try{
                // 接続オブジェクト
            $this->db = new PDO($this->dsn, $this->usr, $this->pwd, $this->opts);
        } catch (PDOException $exc) {
            echo $exc->getMessage();
        }
   }
   
   function send_query($query){
    $this->stt = $this->db->prepare($query);   
   }
   
   function bind($holder, $val){
       $this->stt->bindValue($holder, $val);
   }
   
   function execute(){
       $this->stt->execute();
   }
}

