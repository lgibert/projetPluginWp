<?php
/*
*	Plugin Name: Wp_easy_RSS
*	Plugin URI: http://localhost:8888/wordpressModule/wp-admin/plugins.php
*	Description: Afficher vos flux RSS facilement !!!
*	Version: 1.0
*	Author: LGibert
* Author URI:
*/
// Initialisation widget et fonction
add_action('widgets_init', 'flux_init');

// Enregistrement du widget
function flux_init(){

	register_widget('flux');	

}
//création de la classe pour le widget
class flux extends WP_widget{
//fonction pour la présentation du widget dans le BackOffice 
	function flux(){

		$options = array(
			'classname' => 'flux',
			'description' => 'Un widget pour vos flux RSS'

		);
//Création du widget dans la liste des widgets
		$this->WP_widget('widget_RSS', 'Widget Flux RSS', $options);
	}

//fonction des arguments
	function widget($args, $titreLecteur){

//spécifications pour que le widget soit compatible pour tous les thèmes
		extract($args);
		echo $before_widget;
		echo $before_title.$titreLecteur['titre'].$after_title;
//include de la method
		include_once(ABSPATH . WPINC . '/feed.php');
		$feed = fetch_feed($titreLecteur['url']);
		$maxitems = 0;
//vérification si l'objet est bien créé	
		if ( ! is_wp_error( $feed ) ) : 

// nombre de lien affiché
    $maxitems = $feed->get_item_quantity( 5 ); 

// Construction d'un tableau de tous les items et démarre avec le premier élément (0)
    $rss_items = $feed->get_items( 0, $maxitems );

endif;
?>

<ul class="container">
    <?php if ( $maxitems == 0 ) : ?>
        <li><?php _e( 'No items', 'my-text-domain' ); ?></li>
    <?php else : ?>
    
        <!-- boucle pour chaque item et leur donne leur date et heure de parution au survol -->
        <?php foreach ( $rss_items as $item ) : ?>
            <li>
                <a  href="<?= esc_url( $item->get_permalink() ); ?>"
                    title="<?php printf( __( 'Posted %s', 'my-text-domain' ), $item->get_date('j F Y | g:i a') ); ?>">
                    <?= esc_html( $item->get_title() ); ?>
                </a>
            </li>
            
        <?php endforeach; ?>
    <?php endif; ?>

</ul>
<!--Feuille de style -->
<style><?php include('wp-content/plugins/Wp_easy_RSS/style/style.css') ?></style>

<!--JQuery -->
<script><?php include('wp-content/plugins/Wp_easy_RSS/jQuery/scroll.js') ?></script>

<?php
		echo $after_widget;
	}

//formulaire BackOffice
	function form($titreLecteur){
//titre par défaut
		$default = array(
				'titre' => 'Flux RSS'

			);

		wp_parse_args($titreLecteur,$default)
		
		?>
    <!-- le formulaire et récupération données-->
    <!--titre et champs titre -->
		<p> 
			<label for="<?= $this->get_field_id('titre');?>">
				Titre : 
			</label>
			<input value="<?= $titreLecteur['titre']; ?>" 
					   name="<?= $this->get_field_name('titre'); ?>" 
					   id="<?= $this->get_field_id('titre');?>" 
					   type="text">
		</p>
    <!-- url et champs url -->
		<p>
			<label for="<?= $this->get_field_id('url');?>">
				URL RSS:
			</label>
			<input value="<?= $titreLecteur['url']; ?>" 
					   name="<?= $this->get_field_name('url'); ?>" 
					   id="<?= $this->get_field_id('url');?>" 
					   type="text">	
		</p>
		<?php
	}

//fonction MAJ du formulaire (sauvegarde des nouvelles données)
	function update($new, $old){
		return $new;
	}

}	
?>
