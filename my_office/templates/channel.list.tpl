<?php include('header.tpl')?>
<?php include('welcome.wap.head.tpl')?>
<div class="division">
<a href="?go=channel&do=browse" class="sysiconBtn list">分类列表</a>
<a href="?go=channel&do=append" class="sysiconBtn addorder addproduct">添加分类</a>	

   <table border="0" cellpadding="5" class="gridlist">
      <thead>
        <tr>
          <th >分类名称</th>
          <th><div align="center">排序</div></th>
          <th><div align="center">操作</div></th>
        </tr>
      </thead>
      <?php echo channel::get_channel_table(0,0);?>
    </table>
    </div>
</body>
</html>