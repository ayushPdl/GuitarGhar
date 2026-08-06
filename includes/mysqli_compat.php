<?php
/**
 * mysqli helpers that work without mysqlnd (InfinityFree / unaux etc.)
 * Avoid mysqli_stmt_get_result() — it fatals on hosts without mysqlnd.
 */

if (!function_exists('gg_stmt_fetch_all')) {
    /**
     * @return array<int, array<string, mixed>>
     */
    function gg_stmt_fetch_all($stmt) {
        if (!($stmt instanceof mysqli_stmt)) {
            return [];
        }

        if (function_exists('mysqli_stmt_get_result')) {
            $res = @mysqli_stmt_get_result($stmt);
            if ($res instanceof mysqli_result) {
                $rows = [];
                while ($row = mysqli_fetch_assoc($res)) {
                    $rows[] = $row;
                }
                return $rows;
            }
        }

        $stmt->store_result();
        $meta = $stmt->result_metadata();
        if (!$meta) {
            return [];
        }

        $row = [];
        $bind = [];
        while ($field = $meta->fetch_field()) {
            $row[$field->name] = null;
            $bind[] = &$row[$field->name];
        }
        $meta->free();

        if (!empty($bind)) {
            call_user_func_array([$stmt, 'bind_result'], $bind);
        }

        $rows = [];
        while ($stmt->fetch()) {
            $copy = [];
            foreach ($row as $k => $v) {
                $copy[$k] = $v;
            }
            $rows[] = $copy;
        }
        return $rows;
    }
}

if (!function_exists('gg_stmt_fetch_one')) {
    /**
     * @return array<string, mixed>|null
     */
    function gg_stmt_fetch_one($stmt) {
        $rows = gg_stmt_fetch_all($stmt);
        return isset($rows[0]) ? $rows[0] : null;
    }
}
