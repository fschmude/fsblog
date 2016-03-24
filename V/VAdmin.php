<?
/**
 * Basisklasse für das ganze Backend
 */
require_once 'V/View.php';

abstract class VAdmin extends View {

  protected function displayHead($errmsg, $titel, $showStartLink = true) {
    ?><html>
    <body>
    <head>
    <meta charset="UTF-8">
    <title>FS: <?= $titel ?></title>
    <?
    echo '<link href="'.BASEURL.'img/styles.css" type="text/css" rel="stylesheet">';
    ?>
    <div style="margin:10px; padding:10px; width:900px;" class="tdc">
    <h1>Welcome to the backend</h1>
    
    <table width="100%">
    <tr>
    <td>
    <?
    if ($showStartLink) {
      ?>
      <a href="admin.php"><img src="<?= BASEURL ?>img/fslogo.png" style="border-width:0px; vertical-align:middle;">
      Backend Startseite </a>
      (Artikelübersicht)
      <?
    } else {
      echo '&nbsp;';
    }
    ?>
    </td>
    <td align="right">
    <a href="admin.php?action=logout" style="color:red; font-weight:bold;">Log out</a>
    </td>
    </tr>
    </table>
    
    <?
    if ($errmsg) {
      echo $errmsg;
      exit;
    }
  }
  
  protected function displayFoot() {
    ?>
    <a href="<?= BASEURL ?>index.php">FS-Blog Startseite</a>
    
    <form name="frmNavilink" method="post" action="admin.php">
    <input type="hidden" name="mode" id="mode">
    </form>
    <script type="text/javascript">
    function navilink(mode) {
      this.frmNavilink.mode.value = mode;
      this.frmNavilink.submit();
    }
    </script>
    
    </div>
    </body>
    </html>
    <?
  }

  /**
   * display a hidden form, which serves to launch http posts
   * Usage: display this form, and offer links with href="javascript:launchName(id);"
   * or href="javascript:launchName();"
   * Caution: Dont display a form inside a form!
   * Rather place it at the bottom of your page.
   */
  protected function displayLinkForm($name, $mode) {
    // display form
    echo '<form id="frm'.$name.'" method="post" action="'.BASEURL.'admin.php">';
    echo '<input type="hidden" name="mode" value="'.$mode.'">';
    echo '<input type="hidden" name="id" value="">';
    echo '</form>';
    
    // display launch script
    ?>
    <script type="text/javascript">
    function launch<?= $name ?>(id, question) {
      if (question && !confirm(question)) {
        return;
      }
      
      var frm = document.getElementById('frm<?= $name ?>');
      if (id > 0) {
        frm.id.value = id;
      }
      frm.submit();
    }
    </script>
    <?
  }

  
  protected function displayNaviLink($mode, $label) {
    echo '<a href="javascript:navilink(\''.$mode.'\');">'.$label.'</a>';
  }

  
  protected function displayInputHidden($name, $value) {
    echo '<input type="hidden" name="'.$name.'" value="'.$value.'">'."\n";
  }
  
}

