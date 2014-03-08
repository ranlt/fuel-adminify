<?= \Form::open(array('role' => 'form', 'class' => 'form-horizontal')); ?>
<div class="row">
    <div class="col-lg-6">
        <h3><?= __('menu.menu.config'); ?></h3>
        <div class="well">
            <?= $formLang->field('language')->set_attribute(array('class' => 'form-control')); ?>
            <?= $form->field('slug')->set_attribute(array('class' => 'form-control')); ?>
            <?= $formLang->field('text')->set_attribute(array('class' => 'form-control')); ?>
            <?= $formLang->field('title')->set_attribute(array('class' => 'form-control')); ?>
            <?= $formLang->field('small_desc')->set_attribute(array('class' => 'form-control')); ?>
            <?php if($parent_id == 'none'): ?>
              <?= $form->field('theme')->set_attribute(array('class' => 'form-control')); ?> 
            <?php endif; ?>
            <?= $form->field('is_blank')->set_attribute(array('class' => 'form-control')); ?>
        </div>

        <h3><?= __('menu.menu.link'); ?></h3>
        <div class="well">
          <?= $form->field('use_router')->set_attribute(array('class' => 'form-control')); ?>
          <?= $form->field('link')->set_attribute(array('class' => 'form-control')); ?>

          <div class="named_params">
            <h3><?= __('menu_model_menu.named_params'); ?></span></h3>
            <div class="list-group named_params_container">
              <?php foreach((array)$params as $param): ?>
                <?php $el = str_replace('__id__', uniqid(), \Theme::instance()->view('admin/_prototype/add_param')->set($param)->render()); ?>
                <?= $el; ?>
              <?php endforeach; ?>
            </div>
            <a href="#" class="btn btn-info" id="menu_add_param" data-prototype="<?= e(\Theme::instance()->view('admin/_prototype/add_param')->render()); ?>"><?= __('menu.action.add_param'); ?></a>
          </div>
        </div>
        <br/>

        <section <?php if(empty($eav)) echo 'style="display: none;"'; ?> id="eav" data-prototype="<?= e(\Theme::instance()->view('admin/_prototype/add_attribute')->render()); ?>">
          <h3 class="eav"><?= __('menu.menu.attributes'); ?>  : <span class="theme_name"><?= $themeName; ?></h3>
          <div class="eav well" id="eav_container">
            <?php foreach((array)$eav as $attribute): ?>
                <?php $el = \Theme::instance()->view('admin/_prototype/add_attribute')->set($attribute)->render(); ?>
                <?= $el; ?>
            <?php endforeach; ?>
          </div>
        </section>

        <input type="hidden" id="id_menu" value="<?= $menu->id; ?>" />
        <?= $form->field('add'); ?>
    </div>

    <?php if($parent_id != 'none'): ?>
        <div class="col-lg-6">
            <h2><?= __('menu.menu.select_parent') ?></h2>
            <div id="tree" name="tree"></div>
            <input type="hidden" name="activeNode" id="activeNode" value="<?= $parent_id ?>"/>
        </div>
    <?php endif; ?>
</div>

<?= \Form::close(); ?>


<script type="text/javascript">
    $(function() {

        // Check Use router field
        processUseRouter($('#form_use_router').val());

        $('#form_use_router').on('change', function() {
          processUseRouter($(this).val());
        });

        // Route params
        $('#menu_add_param').on('click', function() {
          var el = $(this).attr('data-prototype');
          $('.named_params_container').append(el.replace(/__id__/g, Math.random()));
          return false;
        });

        function processUseRouter($val)
        {
          if ($val == 1)
          {
            $('#label_link').html("<?= __('menu_model_menu.route_name'); ?>");
            $('.named_params').show();
          }
          else
          {
            $('#label_link').html("<?= __('menu_model_menu.link'); ?>");
            $('.named_params').hide();
          }
        }

        // Change theme => Change EAV
        $('#form_theme').on('change', function() {
          loadEav($('#form_theme').val(), $('#id_menu').val(), false);
        });

        function loadEav($theme, $idMenu, $idRoot)
        {
            var dataReq = {
              isUpdate: <?= ($isUpdate) ? 1 : 0; ?>
            }

            if ($theme) dataReq['theme'] = $theme;
            if ($idMenu) dataReq['idMenu'] = $idMenu;
            if ($idRoot) dataReq['idRoot'] = $idRoot;

            var request = $.ajax({
                url: '/menu/admin/index/api/get_eav.json',
                type: 'GET',
                data: dataReq,
                dataType: 'json'
            });
            
            request.done(function(data) {
                $('#eav_container').html('');
                for(i in data.data){
                  newEav(data.data[i]);
                }

                $('.theme_name').html(data.theme_name);
                (data.data.length > 0) ? $('#eav').show() : $('#eav').hide();
            });
        }

        function newEav(attribute)
        {
          var el = $('#eav').attr('data-prototype');
          el = el.replace(/__key__/g, (attribute.key) ? attribute.key : '');
          el = el.replace(/__label__/g, (attribute.label) ? attribute.label : '');
          el = el.replace(/__value__/g, (attribute.value) ? attribute.value : '');
          $('#eav_container').append(el);
        }



        // Dynatree
        $("#tree").dynatree({
            checkbox: true,
            // Override class name for checkbox icon, so rasio buttons are displayed:
            classNames: {checkbox: "dynatree-radio"},
            selectMode: 1,
            initAjax: {
                url: '/menu/admin/index/api/show_menus.json',
                data: {idSelect: $('#activeNode').val(), show_none: 0 <?php if($isUpdate) echo ', idMenu: "'.$menu->id.'"'; ?>}
            },
            onSelect: function(select, node) {
                $('#activeNode').val(node.data.key);
                loadEav(false, $('#id_menu').val(), node.data.key);
            },
            onDblClick: function(node, event) {
                node.select(true);
                $('#activeNode').val(node.data.key);
            },
            onPostInit: function(isReloading, isError) {
                var tree = $('#tree').dynatree('getTree');
                var selKeys = $.map(tree.getSelectedNodes(), function(node){
                    node.makeVisible();
                });
            }     
        });

        /*
         *   Change language
         */
         var languageVal = $('#form_language').val();

         $('#form_language').on('focus', function() {
            languageVal = $(this).val();
         });
         $('#form_language').on('change', function() {
            bootbox.confirm("<?= __('menu.menu.change_lang_confirm'); ?>", function(result) {
                  if (result) {
                      var request = $.ajax({
                          url: '/menu/admin/index/api/get_menu_lang.json',
                          type: 'GET',
                          data: {id: $('#id_menu').val(), lang: $('#form_language').val()},
                          dataType: 'json'
                      });
                      
                      request.done(function(data) {
                          populateLang(data);
                      });
                  } else {
                      $('#form_language').val(languageVal);
                  }
            }); 
         });

         function populateLang(data)
         {
          $('#form_title').val(data.data.title);
          $('#form_text').val(data.data.text);
          $('#form_small_desc').val(data.data.small_desc);
         }
    });
</script>
