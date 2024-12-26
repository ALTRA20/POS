<?php
function rupiah($number) {
    if ($number) {
        return 'Rp' . number_format($number, 0, ',', '.');
    }
    return null;
}
?>