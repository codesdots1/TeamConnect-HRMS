<script>
    function getColorJS(zone){
        if(zone == "blue"){
            return '<?= $this->dt_ci_common->getColorPHP('blue') ?>';
        }else if(zone == "green"){
            return '<?= $this->dt_ci_common->getColorPHP('green') ?>';
        }else if(zone == "yellow"){
            return '<?= $this->dt_ci_common->getColorPHP('yellow') ?>';
        }else if(zone == "red"){
            return '<?= $this->dt_ci_common->getColorPHP('red') ?>';
        }else if(zone == "black"){
            return '<?= $this->dt_ci_common->getColorPHP('black') ?>';
        }else{
            return zone;
        }
    }


</script>