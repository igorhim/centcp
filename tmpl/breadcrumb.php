        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $title?>
            <small><?php echo $subtitle?></small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="/"><i class="fa fa-dashboard"></i> Home</a></li>
            <?php
            $lastElement = end($path);
            foreach($path as $item) {
                if($lastElement == $item) {
                    echo '<li class="active">' . $item['title'] . '</li>';
                }else {
                    echo '<li><a href="' . $item['url'] . '">' . $item['title'] . '</a></li>';
                }
            }
            ?>
          </ol>
        </section>