<?php

use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

if (!function_exists('translitconvert')) {
    /**
     * This function uploads files to the filesystem of your choice
     * @param \Illuminate\Http\UploadedFile $file The File to Upload
     * @param string|null $filename The file name
     * @param string|null $folder A specific folder where the file will be stored
     * @param string $disk Your preferred Storage location(s3,public,gcs etc)
     */

    function translitconvert(string $text, bool $reverse = false): string
    {
        $map = [
            'А' => 'A_', 'Б' => 'B_', 'В' => 'V_', 'Г' => 'G_', 'Д' => 'D_',
            'Е' => 'E_', 'Ё' => 'JO', 'Ж' => 'ZH', 'З' => 'Z_', 'И' => 'I_',
            'Й' => 'JJ','К' => 'K_', 'Л' => 'L_', 'М' => 'M_', 'Н' => 'N_',
            'О' => 'O_', 'П' => 'P_', 'Р' => 'R_', 'С' => 'S_', 'Т' => 'T_',
            'У' => 'U_', 'Ф' => 'F_', 'Х' => 'KH','Ц' => 'CZ','Ч' => 'CH',
            'Ш' => 'SH','Щ' => 'SC','Ы' => 'Y_',
            'Э' => 'JE','Ю' => 'JU','Я' => 'JA',

            'а' => 'a_', 'б' => 'b_', 'в' => 'v_', 'г' => 'g_', 'д' => 'd_',
            'е' => 'e_', 'ё' => 'jo', 'ж' => 'zh', 'з' => 'z_', 'и' => 'i_',
            'й' => 'jj','к' => 'k_', 'л' => 'l_', 'м' => 'm_', 'н' => 'n_',
            'о' => 'o_', 'п' => 'p_', 'р' => 'r_', 'с' => 's_', 'т' => 't_',
            'у' => 'u_', 'ф' => 'f_', 'х' => 'kh','ц' => 'cz','ч' => 'ch',
            'ш' => 'sh','щ' => 'sc','ы' => 'y_',' ' => '__',
            'э' => 'je','ю' => 'ju','я' => 'ja'
        ];

        if ($reverse) {
            $map = array_flip($map);
            uksort($map, fn($a, $b) => strlen($b) <=> strlen($a));
        }

        return strtr($text, $map);
    }
}
