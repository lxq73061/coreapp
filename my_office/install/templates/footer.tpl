        <?php foreach($hiddens as $hk=>$hv){?>
        <input type="hidden" name="<?php echo $hk?>" value="<?php echo $hv?>" />
        <?php }?>
        </form>
        <form id="gooninstall" method="post">
            <input type="hidden" name="__seccode__" id="__seccode__" />
        </form>
        <div class="foot">&copy; 2009-2010 <a href="http://www.coremvc.cn" target="_blank"><span>CoreMVC system.</span></a></div>
        <div class="clear"></div>
    </div>
    <div class="box_bottom"></div>
    <div class="clear"></div>
</div>
</body>
</html>