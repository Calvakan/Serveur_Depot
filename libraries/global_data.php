<?php
  
  // global configuration
  $ROOT_DIR = getcwd();
  $UPLOAD_DIR = 'uploads';
  $UMASK = 0770;
  $charset = "UTF-8";
  
  $table_promotion_current_year['1A']=htmlentities("1A",ENT_QUOTES, "UTF-8");
  $table_promotion_current_year['2A']=htmlentities("2A",ENT_QUOTES, "UTF-8"); 
  $table_promotion_current_year['3A']=htmlentities("3A",ENT_QUOTES, "UTF-8");
  $table_promotion_current_year['4A']=htmlentities("4A",ENT_QUOTES, "UTF-8");
  $table_promotion_current_year['5A']=htmlentities("5A",ENT_QUOTES, "UTF-8");
  $table_department[1]='InfoTronique';
  $table_department[2]='Matériaux';
  date_default_timezone_set("Europe/Paris");
?>