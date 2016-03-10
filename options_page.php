<?php if( isset($_POST) && !empty($_POST) ){
	//print_r($_POST);
	if( isset($_POST['type']) && !empty($_POST['type']) ){
		unset($_POST['type']);
		unset($_POST['submit']);
		update_option( 'menu_template', $_POST );
	} else {
		unset($_POST['submit']);
		update_option( 'menu_custom_fieds', $_POST );
	}
} ?>
<div class='wrap'> 
<h2>Options of Menu's custom fields </h2>

<h2 class="nav-tab-wrapper amcf-nav-tab-wrapper">
	<?php $url = admin_url( ).'admin.php?page=menu_custom_fields_options'; ?>
	<a href="<?php echo $url;?>&amp;tab=fields" class="nav-tab <?php if( !isset($_GET['tab']) || $_GET['tab']=='fields'){ echo 'nav-tab-active';} ?>">Fields</a>
	<a href="<?php echo $url;?>&amp;tab=templates" class="nav-tab <?php if( isset($_GET['tab']) && $_GET['tab']=='templates'){ echo 'nav-tab-active';} ?>">Templates</a>
</h2>
<div id="menu-options-body" class="metabox-holder columns-2">
	<div class="column1">
		<?php if( !isset($_GET['tab']) || $_GET['tab']=='fields'){  ?>
		<form action="" method="post" id="form_options" class="" onSubmit="return checkRadio();">
			<h3>Fields </h3>
			<?php $fields = get_option( 'menu_custom_fieds' ); 
			if($fields){ ?>
			<div class="header_table">
				<span class="col col-10">ID</span>
				<span class="col col-30">Label/Name</span>
				<span class="col col-20">Type</span>
				<span class="col col-30">Default value. <br/>
					<small>(<span class="dashicons dashicons-info"></span> If type is select or radio specify a comma)</small>
				</span>
				<div class="clearfix"></div>
			</div>
			<ul id="sortable">
				<?php 
				foreach ($fields as $key => $field) { 
					//print_r($field);
					//echo $key; 
					$k = explode('-', $key); ?>
					<li class="portlet button button-secondary clearfix">
						<?php if( $k[1]>count($fields) ){ ?>
						<script type="text/javascript">
						 count = '<?php echo $k[1]+1; ?>';
						 console.log(count);
						</script>
						<?php } ?>
						<span class="ui-widget-header portlet-header col col-10"><?php echo zeroise( $k[1], 2 ); ?></span>
						<input type="text" name="<?php echo $key; ?>[label]" value="<?php echo $field['label']; ?>" class="col col-30"/>
						<select name="<?php echo $key; ?>[type]" class="col col-20 selectTypeField">
							<option value="text" <?php selected('text', $field['type']); ?>>text</option>
							<option value="checkbox" <?php selected('checkbox', $field['type']); ?>>checkbox</option>
							<option value="image" <?php selected('image', $field['type']); ?>>image</option>
							<option value="radio" <?php selected('radio', $field['type']); ?>>radio</option>
							<option value="select" <?php selected('select', $field['type']); ?>>select</option>
						</select>
						<input type="text" name="<?php echo $key; ?>[value]" value="<?php echo $field['value']; ?>" class="col col-30"/>
						<span class="ui-widget-header portlet-header col "><span class="removeLi dashicons dashicons-no" ></span></span>
						
					</li>
				<?php } ?>
			</ul>
			<?php } ?>
			<div><span id="addField" class="button button-secondary col-20 dashicons dashicons-plus">Add new field</span></div>
			<?php submit_button(); ?>
		</form>

		<script>
			function checkRadio(){
				var ret = true;
				jQuery('.selectTypeField').each( function(){
					if( (jQuery(this).val() == 'radio' || jQuery(this).val() == 'select') && jQuery(this).next().val()=='' ){
						alert( 'Fill value for '+jQuery(this).val()+' field');
						ret = false;
						return false;
					}

				});
				return ret;
			}
			var count = 1;
		    count = jQuery('.portlet').length+1;
			function buildLi(count){
				if(count<10){
			    	count = '0'+count;
			    }
				var li = '<li class="portlet button button-secondary clearfix" ><span class="ui-widget-header portlet-header col col-10">'+count+'</span>';
						li += '<input type="text" name="field-'+count+'[label]" value=""  class="col col-30"/>';
						li += '<select name="field-'+count+'[type]" class="col col-20">';
							li += '<option value="text" selected>text</option>';
							li += '<option value="checkbox" >checkbox</option>';
							li += '<option value="image" >image</option>';
							li += '<option value="radio" >radio</option>';
							li += '<option value="select" >select</option>';
						li += '</select>';
						li += '<input type="text" name="field-'+count+'[value]" value=""  class="col col-30"/>';
						li += '<span class="ui-widget-header portlet-header col "><span class="removeLi dashicons dashicons-no" ></span></span>';
					li += '</li>';
				return li;
			}

			jQuery(function() {
			    jQuery( "#sortable" ).sortable();
			    jQuery( "#sortable" ).disableSelection();
			    
			    
			    jQuery( "#addField").click(function(){
			    	var li = buildLi(count);
			    	jQuery( "#sortable" ).append(li);
			    	count++;
			    });
			    
			    jQuery( "#sortable" ).on('click', ".removeLi", function(){
			    	if( confirm('Are you shure?') ){
				    	jQuery(this).closest('.portlet').remove();
			    		count--;
			    	}
			    });
			});
		</script>
		<?php } else { ?>
		<script type="text/javascript">
			  	function checkProp(){
			  		jQuery('[type="radio"]').each(function(){
		  				if( jQuery(this).prop("checked") ){
		  					var id = '#w-'+jQuery(this).attr('id');
		  				jQuery( id ).show();
		  				} else {
			  				jQuery('#w-'+jQuery(this).attr('id') ).hide();
			  			}
			  		});
				}

				jQuery(document).ready(function() {
				  		checkProp();
				    jQuery('[type="radio"]').change( function(){ 
				  		checkProp();
				    });
			    });
			
		</script>
		<form action="" method="post" id="form_template">
			<?php $menu = get_option( 'menu_template' ); 
			//print_r($menu); ?>
			<?php $locs = get_nav_menu_locations(); 
			if($locs){
				foreach ($locs as $k => $m) {
				?>
				<input type="hidden" name="type" value="1">
			<h3>Type of template <?php echo ucfirst($k); ?> menu</h3>
			<input type="radio" name="<?php echo $k; ?>[type]" value="php" id="php-<?php echo $k; ?>" <?php checked('php', $menu[$k]['type']); ?>/><label for="php-<?php echo $k; ?>">Php code for walker</label>
			<input type="radio" name="<?php echo $k; ?>[type]" value="title" id="title-<?php echo $k; ?>" <?php checked('title', $menu[$k]['type']); ?>/><label for="title-<?php echo $k; ?>">HTML code for </label>
			<div class="php_template" id="w-php-<?php echo $k; ?>">
			<label>PHP Template for all menu</label><br>
			<textarea name="<?php echo $k; ?>[php_template]" cols="40" rows="10" ><?php if( !empty($menu[$k]['php_template']) ){
					echo stripslashes($menu[$k]['php_template']);
				} else { ?>
		        $class_names = ' class="' . esc_attr( implode(' ', $item->classes) ) . '"';

		        $output .= $indent . '<li id="menu-item-'. $item->ID . '"' . $value . $class_names .'>';

		        $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
		        $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
		        $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
		        $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';

		        $item_output = $args->before;
		        $item_output .= '<a'. $attributes .'>';

		        $item_output .= $args->link_before;

		        //START Display the image field
		          if(get_post_meta($item->ID, 'menu-item-field-IDENTIFICATOROFFIELD', true) ){
			          $img = image_downsize( get_post_meta($item->ID, 'menu-item-field-IDENTIFICATOROFFIELD', true), 'thumbnail');
			          $item_output .= '<img src="'. $img[0].'" alt="!!!">';
			      }
			    //END Display the image field

		          $item_output .= '<span>'.$item->title.'</span>';

		        //START Use checkbox field
		        if( get_post_meta($item->ID, 'menu-item-field-IDENTIFICATOROFFIELD', true) ){
		        	$item_output .= '<span>'.$item->description.'</span>';
		    	}
		        //END Use checkbox field

		        //START Use radio field
		        if( get_post_meta($item->ID, 'menu-item-field-IDENTIFICATOROFFIELD', true) ){
		        	$item_output .= '<span>'.$item->description.'</span>';
		    	}
		        //END Use radio field

		        //START Display text or select field
		    	if( get_post_meta($item->ID, 'menu-item-field-IDENTIFICATOROFFIELD', true) ){
		        	$item_output .= '<span>'.get_post_meta($item->ID, 'menu-item-field-IDENTIFICATOROFFIELD', true).'</span>';
		    	}
		        //END Display text or select field

		        $item_output .= $args->link_after;

		        $item_output .= '</a>';
		        $item_output .= $args->after;

		        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		   		<?php } ?></textarea><br>
			</div>
			<div class="menu_template" id="w-title-<?php echo $k; ?>">
			<label>Template for Name of menu (html of taf "a")</label><br>
			<textarea name="<?php echo $k; ?>[menu_template]" cols="40" rows="10" ><?php 	if( !empty($menu[$k]['menu_template']) ){
					echo stripslashes($menu[$k]['menu_template']); 
				} else { 
					echo '%title%';
				}?></textarea>
			</div>
			<hr/>
			<?php } } ?>
			<?php submit_button(); ?>
		</form>
		 <?php }  ?>

	</div>
	<div class="column2">
		<?php if( !isset($_GET['tab']) || $_GET['tab']=='fields'){  ?>
			<div class="inner">
				<h2>Add fieds for your menu</h2>
				<p> Добавить новое поле можно нажав на кнопку "Add new field"</p>
				<p> Обязательными полями являются ID и Type. Первый недоступен для исменений, второй имеет поумолчаниюзначение "текстовое" </p>
				<p> Поле Label заполняется для удобства пользователя. </p>
				<p> Значения по умолчанию обязательное для таких типов, как select и radio. При этом значения необходимо указывать через знак "|" или ",", если в сами значениях ее не будет</p>
				<p> Поля являются сортеруемыми для удобства пользователя при редактировании</p>
			</div>
		<?php } else { ?>
			<div class="inner">
				<h2>Изменения внешненго вида меню</h2>
				<p> Для вывода меню на страницах сайта с дополнительными полями можно использовать написание код пхп для отображения тега &#60;li&#62;. (Для продвинутых пользователей!). При этом в уже имеющимся примере кода заменить IDENTIFICATOROFFIELD на идентификационный номер поля с заполненной ранее вами таблицей. 
				<p> Или использовать более легкий способ для изменения тега &#60;a&#62;. Для этого в соответсвующем поле нужно прописать html код со спец тегами взятыми в знаки процентов "%" </p>
				<p> Примеры спецтегов:
					<ul>
						<li>%title% - виведет значение названия пункта меню</li>
						<li>%text-01% - выведет значение текстрового поля с ID = 01 </li>
						<li>%select-01% - выведет значение поля типа select с ID = 01 </li>
						<li>%radio-01% - выведет значение поля типа radio с ID = 01 </li>
						<li>%image-01% - выведет картинку если поле с ID = 01 заполнено </li>
						<li>%checkbox-01-valueForOn-valueForOff% - выведет valueForOn если поле типа checkbox с ID = 01 is checked и valueForOff если поле checkbox is not checked </li>
					</ul>
				</p>
			</div>

		<?php }  ?>
	</div>
	