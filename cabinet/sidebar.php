<?php
  $scrip_name = pathinfo($_SERVER['SCRIPT_NAME'], PATHINFO_BASENAME);
?>
<aside class="sidebar" style="width: 330px;">
          <div class="sidebar-navigation">
            <a class="sidebar-navigation__user" href="#">
              <span class="user-icon">
                <img src="./img/sidebar/user.svg" alt="user icon" />
              </span>
              <span class="user-title"><?php echo ($row_orders['societe'] != "" ? $row_orders['societe'] : $row_orders['last_name']." ".$row_orders['first_name']) ?></span>
            </a>
            <a class="sidebar-navigation__btn<?php if (strpos($scrip_name, 'index') !== false) {echo' sidebar-navigation__btn--welcome';} ?>" href="./">
              <span class="btn-icon">
                <img src="./img/sidebar/house.svg" alt="Accueil svg" />
              </span>
              <span class="btn-title">Accueil</span>
            </a>
            <a
              id="sidebar-navigation__btn--paiement" href="./paiement.php"
              class="sidebar-navigation__btn<?php if (strpos($scrip_name, 'paiement') !== false) {echo' sidebar-navigation__btn--paiement';} if (strpos($row_orders['select_type'], 'entreprise') !== false && ($row_orders['payment_valid'] != 1 || (($row_orders['deposit_link'] != "" && $row_orders['deposit_link_view'] == 0) || ($row_orders['sale_link'] != "" && $row_orders['sale_link_view'] == 0)) && $row_orders['payment_status'] != 2)) {echo' sidebar-navigation__btn--msg';} ?>"
            >
              <span class="btn-icon">
                <img src="./img/sidebar/paiement.svg" alt="Paiement svg" />
              </span>
              <span class="btn-title">Paiement</span>
              <span class="btn-msg">
                <span>1</span>
              </span>
            </a>
            <a id="sidebar-navigation__btn--contour" class="sidebar-navigation__btn<?php if (strpos($scrip_name, 'contour_photo') !== false) {echo' sidebar-navigation__btn--contour';}  if ($row_orders['gallery_valid'] != 1 && $row_orders['without_photo_frame'] != 1 && ($row_orders['image'] == '' || $row_orders['template_status'] == 0)) {echo' sidebar-navigation__btn--msg';} ?>" href="./contour_photo.php">
              <span class="btn-icon">
                <img
                  src="./img/sidebar/contour_photo.png"
                  alt="Contour photo svg"
                />
              </span>
              <span class="btn-title">Contour photo</span>
              <span class="btn-msg">
                <span>1</span>
              </span>
            </a>
            <a id="sidebar-navigation__btn--delivery" class="sidebar-navigation__btn<?php if (strpos($scrip_name, 'delivery') !== false) {echo' sidebar-navigation__btn--delivery';} if (strpos($delivery, 'Retrait boutique') === false && $row_orders['delivery_valid'] != 1) {echo' sidebar-navigation__btn--msg';} ?>" href="./delivery.php">
              <span class="btn-icon">
                <img src="./img/sidebar/delivery.svg" alt="Livraison svg" />
              </span>
              <span class="btn-title"><?php echo $delivery; ?></span>
              <?php if (strpos($delivery, 'Retrait boutique') === false) { ?>
                <span class="btn-msg">
                  <span>1</span>
                </span>
              <?php } ?>
            </a>
            <a class="sidebar-navigation__btn<?php if (strpos($scrip_name, 'gallery') !== false) {echo' sidebar-navigation__btn--gallery';} ?>" href="./gallery.php">
              <span class="btn-icon">
                <img
                  src="./img/sidebar/photo_gallery.svg"
                  alt="Galerie photo svg"
                />
              </span>
              <span class="btn-title">Galerie photo</span>
            </a>
          </div>

          <div class="sidebar-footer">
            <span>Shootnbox</span>
            <a href="mailto:contact@shootnbox.fr">contact@shootnbox.fr</a>
            <a href="tel:+0145016666">01 45 01 66 66</a>
          </div>
        </aside>