<?php include('header.tpl')?>

○<a href="?go=channel&do=browse">分类列表</a>
○<a href="?go=channel&do=append">添加分类</a>	

<table class="table">
      <thead>
        <tr>
          <td >分类名称</td>
          <td width="60"><div align="center">排序</div></td>
          <td width="80"><div align="center">操作</div></td>
        </tr>
      </thead>
      <?php echo channel::get_channel_table(0,0);?>
    </table>
</body>
</html>