<footer class="main-footer noprint">
    <div class="pull-right hidden-xs">
      <b>Version</b> 1.0.0
    </div>
    <?php $settings = get_options(array("copyright","website")); ?>
    <strong><?php echo _l("Copyright"); ?> &copy; 2019-2020 <a href="<?php echo $settings["website"]; ?>"><?php echo $settings["copyright"]; ?></a>.</strong> <?php echo _l("msg_all_right_reserved"); ?> 
</footer>