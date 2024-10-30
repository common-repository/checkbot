function reselect(select_id) {
    document.getElementById('<?php echo $_SESSION['selected']; ?>').value = '0';
    switch(select_id) {
        case 1:
            document.getElementById('<?php echo $_SESSION['first']; ?>').className = 'border_y';
            document.getElementById('<?php echo $_SESSION['second']; ?>').className = 'border_n';
            document.getElementById('<?php echo $_SESSION['third']; ?>').className = 'border_n';
            document.getElementById('<?php echo $_SESSION['selected']; ?>').value = '1';
        break;
        case 2:
            document.getElementById('<?php echo $_SESSION['first']; ?>').className = 'border_n';
            document.getElementById('<?php echo $_SESSION['second']; ?>').className = 'border_y';
            document.getElementById('<?php echo $_SESSION['third']; ?>').className = 'border_n';
            document.getElementById('<?php echo $_SESSION['selected']; ?>').value = '2';
        break;
        case 3:
            document.getElementById('<?php echo $_SESSION['first']; ?>').className = 'border_n';
            document.getElementById('<?php echo $_SESSION['second']; ?>').className = 'border_n';
            document.getElementById('<?php echo $_SESSION['third']; ?>').className = 'border_y';
            document.getElementById('<?php echo $_SESSION['selected']; ?>').value = '3';
        break;
        case 0:
            document.getElementById('<?php echo $_SESSION['first']; ?>').className = 'border_n';
            document.getElementById('<?php echo $_SESSION['second']; ?>').className = 'border_n';
            document.getElementById('<?php echo $_SESSION['third']; ?>').className = 'border_n';
            document.getElementById('<?php echo $_SESSION['selected']; ?>').value = '0';
        break;
        default:
            document.getElementById('<?php echo $_SESSION['selected']; ?>').value = '0';
        break;
    }
    return false;
}