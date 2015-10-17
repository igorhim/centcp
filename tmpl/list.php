      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <?php include('breadcrumb.php')?>
        
        <link rel="stylesheet" href="/src/AdminLTE/plugins/iCheck/flat/blue.css">
        <script src="/src/AdminLTE/plugins/iCheck/icheck.min.js"></script>
    <!-- Page Script -->
    <script>
      $(function () {
        //Enable iCheck plugin for checkboxes
        //iCheck for checkbox and radio inputs
        $('.mailbox-messages input[type="checkbox"]').iCheck({
          checkboxClass: 'icheckbox_flat-blue',
          radioClass: 'iradio_flat-blue'
        });

        //Enable check and uncheck all functionality
        $(".checkbox-toggle").click(function () {
          var clicks = $(this).data('clicks');
          if (clicks) {
            //Uncheck all checkboxes
            $(".mailbox-messages input[type='checkbox']").iCheck("uncheck");
            $(".fa", this).removeClass("fa-check-square-o").addClass('fa-square-o');
          } else {
            //Check all checkboxes
            $(".mailbox-messages input[type='checkbox']").iCheck("check");
            $(".fa", this).removeClass("fa-square-o").addClass('fa-check-square-o');
          }
          $(this).data("clicks", !clicks);
        });

      });
    </script>
        
      <section class="content">
        <?php
        if(!empty($list['url']['add'])) {
        ?>
        <a class="btn btn-app" href="<?php echo $list['url']['add']?>">
            <i class="fa fa-plus-square"></i> Create
        </a>
        <?php } ?>
        <?php
        if(!empty($list['url']['sync'])) {
        ?>
        <a class="btn btn-app" href="<?php echo $list['url']['sync']?>">
            <i class="fa fa-repeat"></i> Resync
        </a>
        <?php } ?>
        <div class="box box-primary">
                <div class="box-header with-border">
                  <h3 class="box-title"><?php echo $list['title']?></h3>
                  <div class="box-tools pull-right">
                    <div class="has-feedback">
                      <input type="text" placeholder="Search" class="form-control input-sm">
                      <span class="glyphicon glyphicon-search form-control-feedback"></span>
                    </div>
                  </div><!-- /.box-tools -->
                </div><!-- /.box-header -->
                <div class="box-body no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm list-action-delete"><i class="fa fa-trash-o"></i></button>
                      <!--<button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>-->
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm list-action-refresh"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      <?php echo ($list['data'] > 0 ? '1' : '0') . '-' . count($list['data']) . '/' . count($list['data']); ?>
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                  <div class="table-responsive mailbox-messages">
                    <table class="table table-hover table-striped">
                      <tbody>
                        <tr>
                        <th></th>
                        <?php
                        foreach($list['fields'] as $alias => $field) {
                            if(!empty($field['hidden']) && in_array('list', $field['hidden'])) continue;
                            echo '<th>' . ($field['title']) . '</th>';
                        }
                        foreach($list['data'] as $row) {
                        ?>
                        </tr>
                        <tr>
                          <td><input type="checkbox" name="items[]" class="list-items" style="position: absolute; opacity: 0;" value="<?php echo $row[$list['key']]?>"></td>
                          <?php
                          $url = $list['url']['edit'];
                          $url = str_replace('{' . $list['key'] . '}', $row[$list['key']], $url);
                          $first = true;
                          foreach($list['fields'] as $alias => $field) {
                            if(!empty($field['hidden']) && in_array('list', $field['hidden'])) continue;
                            if($field['type'] == 'link') {
                                echo '<td><a href="' . $field['url'] . '">' . $field['link'] . ' </a></td>'; 
                            }else {
                                echo '<td>' . ($first ? '<a href="' . $url . '">' : '') . $row[$alias] . ($first ? '</a>' : '') . '</td>';
                            }
                            $first = false;
                          }
                          ?>
                        </tr>
                        <?php } ?> 
                      </tbody>
                    </table><!-- /.table -->
                  </div><!-- /.mail-box-messages -->
                </div><!-- /.box-body -->
                <div class="box-footer no-padding">
                  <div class="mailbox-controls">
                    <!-- Check all button -->
                    <button class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i></button>
                    <div class="btn-group">
                      <button class="btn btn-default btn-sm list-action-delete"><i class="fa fa-trash-o"></i></button>
                      <!--<button class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                      <button class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>-->
                    </div><!-- /.btn-group -->
                    <button class="btn btn-default btn-sm list-action-refresh"><i class="fa fa-refresh"></i></button>
                    <div class="pull-right">
                      <?php echo ($list['data'] > 0 ? '1' : '0') . '-' . count($list['data']) . '/' . count($list['data']); ?>
                      <div class="btn-group">
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></button>
                        <button class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></button>
                      </div><!-- /.btn-group -->
                    </div><!-- /.pull-right -->
                  </div>
                </div>
              </div>
      </section>
      </div>