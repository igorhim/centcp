      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?php include('breadcrumb.php')?>
        
        <section class="content">
        <div class="box box-<?php echo $form['box']?>">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $form['title']?></h3>
                </div><!-- /.box-header -->
                <?php
                if(!empty($form['error'])) {
                ?>
                <div class="callout callout-danger margin">
                    <h4><?php echo $form['error'] ?></h4>
                </div>
                <?php
                }
                ?>
                <!-- form start -->
                <form role="form" action="<?php echo $form['action']?>" method="post">
                  <div class="box-body">
                    <?php
                    
                    if(!empty($form['item'])) {
                        echo '<input type="hidden" name="' . $form['key'] . '" value="' . $form['item'][$form['key']] . '" />';
                    }
                    
                    foreach($form['fields'] as $alias => $field) {
                        
                        if(!empty($field['hidden']) && in_array('form', $field['hidden'])) continue;
                        
                        switch($field['type']) {
                            case 'email':
                                echo '
                                <div class="form-group">
                                  <label for="input' . $alias . '">' . $field['title'] . (!empty($field['required']) ? ' *' : '') .  '</label>
                                  <input type="email" name="' . $alias . '" placeholder="Enter email" id="input' . $alias . '" class="form-control" ' . (isset($form['item'][$alias]) ? 'value="' . $form['item'][$alias] . '"' : '') . '>
                                </div>
                                ';
                                break;
                            case 'text':
                                echo '
                                <div class="form-group">
                                  <label for="input' . $alias . '">' . $field['title'] . (!empty($field['required']) ? ' *' : '') . '</label>
                                  <input type="text" name="' . $alias . '" placeholder="' . $field['title'] . '" id="input' . $alias . '" class="form-control" ' . (isset($form['item'][$alias]) ? 'value="' . $form['item'][$alias] . '"' : '') . '>
                                </div>
                                ';
                                break;
                            case 'password':
                                echo '
                                <div class="form-group">
                                  <label for="input' . $alias . '">' . $field['title'] . (!empty($field['required']) ? ' *' : '') . '</label>
                                  <input type="password" name="' . $alias . '" placeholder="' . $field['title'] . '" id="input' . $alias . '" class="form-control">
                                </div>
                                ';
                                break;
                            case 'checkbox':
                                echo '
                                <div class="checkbox">
                                <input type="hidden" name="' . $alias . '" value="0" />
                                <label>
                                    <input type="checkbox" name="' . $alias . '"  ' . (isset($form['item'][$alias]) ? 'checked="checked"' : '') . '> ' . $field['title'] . '
                                </label>
                                </div>
                                ';
                                break;
                            case 'textarea':
                                echo '
                                <div class="form-group">
                                  <label>' . $field['title'] . (!empty($field['required']) ? ' *' : '') . '</label>
                                  <textarea placeholder="' . $field['title'] . '" name="' . $alias . '" rows="3" class="form-control"> ' . (isset($form['item'][$alias]) ? $form['item'][$alias] : '') . '</textarea>
                                </div>
                                ';
                                break;
                            case 'select':
                                $options = '';
                                foreach($field['options'] as $option) {
                                    $options .= '<option value="' . $option['value'] . '" ' . (isset($form['item'][$alias]) && $option['value'] == $form['item'][$alias] ? 'selected="selected"' : '') . '>' . $option['title'] . '</option>' . PHP_EOL;
                                }
                                echo '
                                <div class="form-group">
                                  <label>' . $field['title'] . '</label>
                                  <select class="form-control" name="' . $alias . '">
                                    ' . $options . '
                                  </select>
                                </div>
                                ';
                                break;
                        }
                    } ?>
                  </div><!-- /.box-body -->

                  <div class="box-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                  </div>
                </form>
        </div>
        </section>
      </div>