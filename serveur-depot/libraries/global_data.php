<?php
  
  // global configuration
  $ROOT_DIR = getcwd();
  $UPLOAD_DIR = 'uploads';
  $UMASK = 0770;
  $charset = "UTF-8";
  
  $table_promotion_current_year['1A']=htmlentities("1ère année",ENT_QUOTES, "UTF-8");
  $table_promotion_current_year['2A']=htmlentities("2ème année",ENT_QUOTES, "UTF-8"); 
  $table_promotion_current_year['3A']=htmlentities("3ème année",ENT_QUOTES, "UTF-8");
  $table_promotion_current_year['4A']=htmlentities("4ème année",ENT_QUOTES, "UTF-8");
  $table_promotion_current_year['5A']=htmlentities("5ème année",ENT_QUOTES, "UTF-8");
  $table_department[1]='infoTronique';
  $table_department[2]='materiaux';
    
    date_default_timezone_set("Europe/Paris");
    
  ?>