<?
/**
 * View of all articles
 */
require_once 'V/VAdmin.php';

class VAdminArtikelList extends VAdmin {
  
  public function display($errmsg, $data) {
    $this->displayHead($errmsg, 'Artikelübersicht');

    echo ' -&gt; ';
    $this->displayNaviLink('Leser_list', 'Abonnenten verwalten');
    echo ' &nbsp; -&gt; ';
    $this->displayNaviLink('Bild_list', 'Bilder verwalten');
    echo ' &nbsp; -&gt; ';
    $this->displayNaviLink('Snippet_list', 'Schnippel verwalten', date('Ym'));
    echo ' &nbsp; -&gt; ';
    $this->displayNaviLink('Video_list', 'Videos verwalten');
    echo '<br><br>'."\n";

    $this->displayNaviLink('Artikel_new', 'Neuen Artikel anlegen');
    echo '<br><br>'."\n";

    // Liste anzeigen
    if (!count($data['rows'])) {
      echo 'Es konnten keine Artikel gefunden werden.';
    
    } else {
      ?>
      <table class="tded">
      <tr>
      <td class="tded">ID</td>
      <td class="tded">Erscheinungsdatum</td>
      <td class="tded">Titel &amp; URL (click to view)</td>
      <td class="tded">Status</td>
      <td class="tded">Edit</td>
      <td class="tded">Delete</td>
      <td class="tded">Mailtext</td>
      </tr>
      <?
      foreach ($data['rows'] as $artikel) {
        ?>
        <tr>
        <td class="tded"><?= $artikel['id'] ?></td>
        <td class="tded"><?= date('Y-m-d', strtotime($artikel['datum'])) ?></td>
        <td class="tded"><a href="<?= $artikel['url'] ?>" target="_blank"><?= $artikel['titel'] ?></a></td>
        <td class="tded"><?= $artikel['status'] ?></td>
        <td class="tded" style="text-align:center;">
        <?
        $this->displayEditIcon('Artikel_up1', $artikel['id']);
        ?>
        </td>
        <td class="tded" style="text-align:center;">
        <?
        $this->displayDelIcon('Artikel_del', 'Artikel', $artikel['id']);
        ?>
        </td>
        <td class="tded">
          <?
          if ($artikel['status'] == 1) {
            echo '<a href="mailtext.php?aid='.$artikel['id'].'" target="_blank">Mailtext</a>';
          } else {
            echo 'Status != 1';
          }
          ?>
        </td>
        </tr>
        <?
      }
      ?>
      </table>
      <?
    }
    
    $this->displayFoot();
  }

}

