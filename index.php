<?php 
if(  is_dir( 'install')  == true ){
    header("Location:./install");
}else{
    echo 'dist';
}
