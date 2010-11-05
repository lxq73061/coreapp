<?php include 'header.tpl'?>
        <style type="text/css">
        .agreement { height:400px; }
        .text { border-top:none; }
        .text p, .text h4 { margin-top:10px; }
        </style>
        <script type="text/javascript">
        $(function(){
            $('#post_form').submit(function(){
                if (!$('#accept:checked').val())
                {
                    alert('<?php echo $lang[accept_first]?>');

                    return false;
                }
                return true;
            });
            $('#accept').click(function(){
                $('#submit_button').get(0).disabled = !this.checked;
            });
            $('#submit_button').get(0).disabled = !$('#accept').get(0).checked;
        });
        </script>
        <div class="agreement">
            <div class="text">
                <?php echo $eula?>
            </div>
            <div class="accede">
                <input type="checkbox" name="accept" id="accept" value="1" />&nbsp;&nbsp;<label for="accept"><?php echo $lang[i_accept]?></label>
            </div>
        </div>
        <div class="btn">
            <input class="button mr10" type="button" value="<?php echo $lang[quit_install]?>" onclick="window.close();" />
            <input type="submit" id="submit_button" value="<?php echo $lang[next]?>" class="button" />
        </div>
<?php include 'footer.tpl'?>