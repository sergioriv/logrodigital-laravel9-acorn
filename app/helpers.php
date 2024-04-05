<?php
if (! function_exists('imgBase64')) {
    function imgBase64($img_path)
    {
        $opciones_ssl = array(
            "ssl" => array(
                "verify_peer" => false,
                "verify_peer_name" => false,
            ),
        );

        $extencion = pathinfo($img_path, PATHINFO_EXTENSION);
        $data = file_get_contents($img_path, false, stream_context_create($opciones_ssl));
        $img_base_64 = base64_encode($data);

        return 'data:image/' . $extencion . ';base64,' . $img_base_64;
    }
}

if (! function_exists('dateInLetters')) {
    function dateInLetters(\Datetime $date = null) {
        if (is_null($date)) $date = Carbon\Carbon::now();
        return $date->format('j') . ' dias del mes de ' . \App\Consts::mounthshortText($date->format('n')) . ' de ' . $date->format('Y');
    }
}
