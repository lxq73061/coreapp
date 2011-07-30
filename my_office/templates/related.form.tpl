<?php include('header.tpl')?>

<form method="get" action="?go=related&do=<?php echo $_GET['do']; ?>&s_type=<?php echo $_GET['s_type']; ?>&s_id=<?php echo $_GET['s_id']; ?>&t_type=<?php echo $_GET['t_type']; ?>&t_id=<?php echo $_GET['t_id']; ?>&<?php if(isset($_GET['query'])): ?>&query=<?php echo urlencode($_GET['query']); ?><?php endif; ?>">
  <input type="hidden" name="go" value="related" />
  <input type="hidden" name="do" value="<?php echo $_GET['do']; ?>" />
  <fieldset>
    <legend><?php echo isset ($_GET ['do']) && $_GET ['do'] === 'append' ? '添加分类' : '修改分类'; ?></legend>
    
    <label>源类型：
      <select name="s_type" id="s_type" onchange="this.form.submit()">
        <option value="">-----请选择-----</option>
        <?php foreach($types as $k=>$v):?>
        <option value="<?=$k?>" <?=($k==$get[s_type]?"selected":"")?>>
        <?=$v?>
        </option>
        <?php endforeach;?>
      </select>
      
    </label>
    <?php if(isset($error['s_type'])): ?><font color="red"><?php echo $error['s_type']; ?></font><?php endif; ?>
    <br>
    <?php if($get['s_type']):?>
    <label>源内容：
      <select name="s_id" id="s_id" onchange="this.form.submit()">
        <option value="">-----请选择-----</option>
        <?=$s_list?>
      </select>
    </label>    
   
    <?php if(isset($error['s_id'])): ?><font color="red"><?php echo $error['s_id']; ?></font><?php endif; ?>
    <br>
    <?php endif?>
    
    
    <label>目标类型：
      <select name="t_type" id="t_type" onchange="this.form.submit()">
        <option value="">-----请选择-----</option>
        <?php foreach($types as $k=>$v):?>
        <option value="<?=$k?>" <?=($k==$get[t_type]?"selected":"")?>>
        <?=$v?>
        </option>
        <?php endforeach;?>
      </select>
    </label>
    <?php if(isset($error['t_type'])): ?><font color="red"><?php echo $error['t_type']; ?></font><?php endif; ?>
    <br>
    
    <?php if($get['t_type']):?>
   
    <label>目标内容：
      <select name="t_id" id="t_id" onchange="this.form.submit()">
        <option value="">-----请选择-----</option>
        <?=$t_list?>
      </select>
    </label>    

    <?php if(isset($error['t_id'])): ?><font color="red"><?php echo $error['t_id']; ?></font><?php endif; ?>
    <br>  
     <?php endif?>  
    
    
    <label>
      <input onclick="this.form.method='post'" type="submit" value="提交">
    </label>
  </fieldset>
</form>
</body></html>