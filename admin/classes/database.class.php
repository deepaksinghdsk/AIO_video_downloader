<?php

class database
{
    /**
     * @var PDO
     */

    public static $db;

    public static function connect($dsn, $username, $password)
    {
        self::$db = new PDO($dsn, $username, $password);
        self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        self::$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    public static function table_status()
    {
        $stmt = self::$db->query("SHOW TABLE STATUS", PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return $rows;
        }
    }

    public static function row_list($table, $order_by = "ID")
    {
        $stmt = self::$db->query("SELECT * FROM '$table' ORDER BY '$order_by'", PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        if (!empty($rows)) {
            return $rows;
        } else {
            return false;
        }
    }

    public static function list_contents()
    {
        $stmt = self::$db->prepare("SELECT * FROM contents ORDER BY content_title");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            return $rows;
        } else {
            return false;
        }
    }

    public static function list_proxies()
    {
        $stmt = self::$db->prepare("SELECT * FROM proxies ORDER BY ID");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            return $rows;
        } else {
            return false;
        }
    }

    public static function list_downloads($limit = 30)
    {
        $stmt = self::$db->prepare("SELECT * FROM downloads ORDER BY download_date DESC LIMIT $limit");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!empty($rows)) {
            return $rows;
        } else {
            return false;
        }
    }

    public static function find($query)
    {
        $stmt = self::$db->query($query, PDO::FETCH_ASSOC);
        $stmt->execute();
        $row = $stmt->fetchAll();
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public static function find_user($email)
    {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE user_email=:email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $row;
        } else {
            return false;
        }
    }

    public static function find_content($content_id)
    {
        $stmt = self::$db->prepare("SELECT * FROM contents WHERE ID=:id LIMIT 1");
        $stmt->bindParam(':id', $content_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public static function find_random_proxy()
    {
        $stmt = self::$db->prepare("SELECT * FROM proxies WHERE banned=0 ORDER BY RAND() LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt = self::$db->prepare("UPDATE proxies SET usage_count = usage_count + 1 WHERE ip =:ip");
        $stmt->bindParam(':ip', $row["ip"], PDO::PARAM_STR);
        $stmt->execute();
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public static function find_proxy($proxy_id)
    {
        $proxy_id = (int)$proxy_id;
        $stmt = self::$db->prepare("SELECT * FROM proxies WHERE ID=:id LIMIT 1");
        $stmt->bindParam(':id', $proxy_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public static function find_option($option_name)
    {
        $stmt = self::$db->prepare("SELECT * FROM options WHERE option_name=:name LIMIT 1");
        $stmt->bindParam(':name', $option_name, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public static function slug_exists($slug)
    {
        $stmt = self::$db->prepare("SELECT * FROM contents WHERE content_slug=:slug LIMIT 1");
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public static function check_password($email, $password)
    {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE user_email=:email AND user_pass=:password LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public static function check_password_legacy($email, $password)
    {
        $stmt = self::$db->prepare("SELECT * FROM users WHERE user_email='$email' AND user_pass='$password' LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!empty($row)) {
            return $row;
        } else {
            return false;
        }
    }

    public static function delete_content($content_id)
    {
        $stmt = self::$db->prepare("DELETE FROM contents WHERE ID=:id");
        $stmt->bindParam(':id', $content_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function delete_proxy($proxy_id)
    {
        $stmt = self::$db->prepare("DELETE FROM proxies WHERE ID=:id");
        $stmt->bindParam(':id', $proxy_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function delete_stats($days = 30)
    {
        $stmt = self::$db->prepare("DELETE FROM downloads WHERE download_date < (NOW() - INTERVAL :days DAY)");
        $stmt->bindParam(':days', $days, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function update_option($option_name, $option_value)
    {
        $stmt = self::$db->prepare("UPDATE options SET option_value=:option_value WHERE option_name=:option_name");
        $update = $stmt->execute(array(
            "option_name" => $option_name,
            "option_value" => $option_value
        ));
        return $update;
    }

    public static function update_content($content)
    {
        $stmt = self::$db->prepare("UPDATE contents SET content_text=:content_text, content_slug=:content_slug, content_title=:content_title, content_opt=:content_opt, content_description=:content_description, content_type=:content_type WHERE ID=:content_id");
        $update = $stmt->execute(array(
            "content_id" => $content["id"],
            "content_slug" => $content["slug"],
            "content_text" => $content["text"],
            "content_title" => $content["title"],
            "content_description" => $content["description"],
            "content_opt" => $content["opt"],
            "content_type" => $content["type"]
        ));
        return $update;
    }

    public static function update_password($user_email, $new_password)
    {
        $stmt = self::$db->prepare("UPDATE users SET user_pass=:new_password WHERE user_email=:user_email");
        $update = $stmt->execute(array(
            "new_password" => $new_password,
            "user_email" => $user_email
        ));
        return $update;
    }

    public static function update_proxy($proxy)
    {
        $stmt = self::$db->prepare("UPDATE proxies SET ip=:ip, port=:port, type=:type, username=:proxy_user, password=:proxy_pass, usage_count=:usage_count, banned=:banned WHERE ID=:id");
        $update = $stmt->execute(array(
            "id" => $proxy["id"],
            "ip" => $proxy["ip"],
            "port" => $proxy["port"],
            "type" => $proxy["type"],
            "proxy_user" => $proxy["username"],
            "proxy_pass" => $proxy["password"],
            "usage_count" => $proxy["usage_count"],
            "banned" => $proxy["banned"]
        ));
        return $update;
    }

    public static function create_column($table, $column_name, $type)
    {
        $stmt = self::$db->prepare(sprintf("ALTER TABLE %s ADD %s %s", $table, $column_name, $type));
        $alter = $stmt->execute();
        return $alter;
    }

    public static function create_option($option)
    {
        $stmt = self::$db->prepare("INSERT INTO options SET option_name=:option_name option_value=:option_value ");
        $insert = $stmt->execute(array(
            "option_name" => $option["name"],
            "option_value" => $option["value"]
        ));
        return $insert;
    }

    public static function create_content($content)
    {
        $stmt = self::$db->prepare("INSERT INTO contents SET content_text=:content_text, content_slug=:content_slug, content_title=:content_title, content_opt=:content_opt, content_description=:content_description, content_type=:content_type");
        $insert = $stmt->execute(array(
            "content_slug" => $content["slug"],
            "content_text" => $content["text"],
            "content_title" => $content["title"],
            "content_description" => $content["description"],
            "content_opt" => $content["opt"],
            "content_type" => $content["type"]
        ));
        return $insert;
    }

    public static function create_proxy($proxy)
    {
        $stmt = self::$db->prepare("INSERT INTO proxies SET ip=:ip, port=:port, type=:type, username=:proxy_user, password=:proxy_pass, usage_count=:usage_count, banned=:banned");
        $insert = $stmt->execute(array(
            "ip" => $proxy["ip"],
            "port" => $proxy["port"],
            "type" => $proxy["type"],
            "proxy_user" => $proxy["username"],
            "proxy_pass" => $proxy["password"],
            "usage_count" => $proxy["usage_count"],
            "banned" => $proxy["banned"]
        ));
        return $insert;
    }

    public static function create_log($data)
    {
        $download_source = $data["source"];
        $download_links = $data["links"];
        unset($data["source"]);
        unset($data["links"]);
        $download_meta = $data;
        $stmt = self::$db->prepare("INSERT INTO downloads SET download_meta = ?, download_links = ?, download_source = ?");
        $insert = $stmt->execute(array(
            json_encode($download_meta), "", $download_source
        ));
        return $insert;
    }

    public static function allTotal($date = '')
    {
        switch ($date) {
            case 'today':
                $query = "SELECT * FROM downloads WHERE DATE(`download_date`) = CURDATE()";
                break;
            case 'yesterday':
                $query = "SELECT * FROM downloads WHERE download_date BETWEEN CURDATE() - INTERVAL 1 DAY AND CURDATE()";
                break;
            case 'this_week':
                $query = "SELECT * FROM downloads WHERE WEEK(download_date, 1) = WEEK(CURDATE(), 1)";
                break;
            case 'week':
                $query = "SELECT * FROM downloads WHERE download_date >= DATE_SUB(CURDATE(), INTERVAL 1 WEEK)";
                break;
            case 'month':
                $query = "SELECT * FROM downloads WHERE download_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
                break;
            case 'all':
                $query = "SELECT * FROM downloads";
                break;
            default:
                $query = "SELECT * FROM downloads";
                break;
        }
        $stmt = self::$db->query($query, PDO::FETCH_ASSOC);
        $stmt->execute();
        return $stmt->rowCount();
    }

    public static function website_stats()
    {
        $stmt = self::$db->query("SELECT * FROM downloads", PDO::FETCH_ASSOC);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $website_stats = array();
        $website_stats["total"] = 0;
        foreach ($rows as $row) {
            if (!isset($website_stats[$row["download_source"]])) {
                $website_stats[$row["download_source"]] = 0;
            }
            $website_stats[$row["download_source"]] += 1;
            $website_stats["total"] += 1;
        }
        return $website_stats;
    }

    public static function formatted_stats()
    {
        $cache = get_stats_cache();
        if ($cache != false) {
            return $cache;
        } else {
            $website_stats = self::website_stats();
            $i = 0;
            $stats = array();
            foreach ($website_stats as $key => $value) {
                $stats[$i]["title"] = $key;
                $stats[$i]["value"] = $value;
                $i++;
            }
            function sort_by_size($a, $b)
            {
                return $b["value"] - $a["value"];
            }

            usort($stats, 'sort_by_size');
            file_put_contents(__DIR__ . '/../../system/storage/temp/stats.json', json_encode($stats));
            return $stats;
        }
    }

    public static function monthly_stats()
    {
        $cache = get_stats_cache(__DIR__ . '/../../system/storage/temp/stats-monthly.json');
        if ($cache != false) {
            return $cache;
        } else {
            $query = "SELECT download_date FROM downloads WHERE download_date >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            $stmt = self::$db->query($query, PDO::FETCH_ASSOC);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            $group = array();
            foreach ($rows as $row) {
                if (empty($group[date('d/m/Y', strtotime($row["download_date"]))])) {
                    $group[date('d/m/Y', strtotime($row["download_date"]))] = 0;
                }
                $group[date('d/m/Y', strtotime($row["download_date"]))]++;
            }
            $group_2 = array();
            foreach ($group as $key => $value) {
                array_push($group_2, ["date" => $key, "count" => $value]);
            }
            file_put_contents(__DIR__ . '/../../system/storage/temp/stats-monthly.json', json_encode($group_2));
            return $group_2;
        }
    }
}