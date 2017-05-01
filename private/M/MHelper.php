<?
/**
 * Schlamperkisten-Modul
 */
class MHelper {

  /**
   * Produce a new code
   */
  public function makeCode() {
    $code = '';
    for ($i=1;$i<=15;$i++) {
      $code .= chr(rand(65,90));
    }
    return $code;
  }

}
