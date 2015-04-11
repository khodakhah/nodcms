<?php
   
    define('INST_RUNSCRIPT', pathinfo(__FILE__, PATHINFO_BASENAME));
    define('INST_BASEDIR',	 str_replace(INST_RUNSCRIPT, '', __FILE__));
    define('INST_RUNFOLDER', 'installer/');
	define('INST_RUNINSTALL', 'installer.php');
    if (is_dir(INST_BASEDIR.INST_RUNFOLDER) && 
		is_readable(INST_BASEDIR.INST_RUNFOLDER.INST_RUNINSTALL))
        require(INST_BASEDIR.INST_RUNFOLDER.INST_RUNINSTALL);
                 
    /* ================================================================= */
?>


<h1>Installed Free nodCMS Script successful!</h1>
<a href="../">Go to nodCMS</a>
