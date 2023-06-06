<?php
function get_domain($url)
{
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return false;
}

function build_menu($footer = false)
{
    $menu = json_decode(option("theme.menu"), true);
    if (!empty($menu)) {
        foreach ($menu as $node) {
            $node['target'] = isset($node['target']) ? $node['target'] : "_blank";
            if (!empty($node['title']) && !empty($node['url']) && !empty($node['target'])) {
                if ($footer === true) {
                    echo '<li><a target="' . $node['target'] . '" href="' . $node['url'] . '">' . $node['title'] . '</a></li>';
                } else {
                    echo '<li class="nav-item"><a target="' . $node['target'] . '" class="nav-link" href="' . $node['url'] . '">' . $node['title'] . '</a></li>';
                }
            }
        }
    }
}

function social_links()
{
    $social_links = json_decode(option("theme.general"), true);
    foreach ($social_links as $link => $key) {
        if (!empty($key)) {
            switch ($link) {
                case 'facebook':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-facebook" href="https://facebook.com/' . $key . '"><i class="fab fa-facebook-f"></i></a>';
                    break;
                case 'twitter':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-twitter" href="https://twitter.com/' . $key . '"><i class="fab fa-twitter"></i></a>';
                    break;
                case 'youtube':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-youtube" href="https://youtube.com/' . $key . '"><i class="fab fa-youtube"></i></a>';
                    break;
                case 'instagram':
                    echo '<a class="btn btn-sm btn-social btn-fill btn-instagram" href="https://instagram.com/' . $key . '"><i class="fab fa-instagram"></i></a>';
                    break;
            }
        }
    }
}

function get_language_info($country_code)
{
    /**
     * Icons made by Freepik @url https://www.flaticon.com/authors/freepik
     * from www.flaticon.com
     */
    switch ($country_code) {
        case "en":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#f0f0f0"/><g fill="#0052b4"><path d="M52.92 100.142c-20.109 26.163-35.272 56.318-44.101 89.077h133.178L52.92 100.142zM503.181 189.219c-8.829-32.758-23.993-62.913-44.101-89.076l-89.075 89.076h133.176zM8.819 322.784c8.83 32.758 23.993 62.913 44.101 89.075l89.074-89.075H8.819zM411.858 52.921c-26.163-20.109-56.317-35.272-89.076-44.102v133.177l89.076-89.075zM100.142 459.079c26.163 20.109 56.318 35.272 89.076 44.102V370.005l-89.076 89.074zM189.217 8.819c-32.758 8.83-62.913 23.993-89.075 44.101l89.075 89.075V8.819zM322.783 503.181c32.758-8.83 62.913-23.993 89.075-44.101l-89.075-89.075v133.176zM370.005 322.784l89.075 89.076c20.108-26.162 35.272-56.318 44.101-89.076H370.005z"/></g><g fill="#d80027"><path d="M509.833 222.609H289.392V2.167A258.556 258.556 0 00256 0c-11.319 0-22.461.744-33.391 2.167v220.441H2.167A258.556 258.556 0 000 256c0 11.319.744 22.461 2.167 33.391h220.441v220.442a258.35 258.35 0 0066.783 0V289.392h220.442A258.533 258.533 0 00512 256c0-11.317-.744-22.461-2.167-33.391z"/><path d="M322.783 322.784L437.019 437.02a256.636 256.636 0 0015.048-16.435l-97.802-97.802h-31.482v.001zM189.217 322.784h-.002L74.98 437.019a256.636 256.636 0 0016.435 15.048l97.802-97.804v-31.479zM189.217 189.219v-.002L74.981 74.98a256.636 256.636 0 00-15.048 16.435l97.803 97.803h31.481zM322.783 189.219L437.02 74.981a256.328 256.328 0 00-16.435-15.047l-97.802 97.803v31.482z"/></g></svg>';
            $name = "English";
            break;
        case "tr":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#d80027"/><g fill="#f0f0f0"><path d="M245.518 209.186l21.005 28.945 34.017-11.03-21.038 28.92 21.002 28.944-34.005-11.072-21.037 28.92.022-35.761-34.006-11.072 34.018-11.03z"/><path d="M188.194 328.348c-39.956 0-72.348-32.392-72.348-72.348s32.392-72.348 72.348-72.348c12.458 0 24.18 3.151 34.414 8.696-16.055-15.702-38.012-25.392-62.24-25.392-49.178 0-89.043 39.866-89.043 89.043s39.866 89.043 89.043 89.043c24.23 0 46.186-9.691 62.24-25.392-10.234 5.547-21.956 8.698-34.414 8.698z"/></g></svg>';
            $name = "Türkçe";
            break;
        case "de":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M15.923 345.043C52.094 442.527 145.929 512 256 512s203.906-69.473 240.077-166.957L256 322.783l-240.077 22.26z" fill="#ffda44"/><path d="M256 0C145.929 0 52.094 69.472 15.923 166.957L256 189.217l240.077-22.261C459.906 69.472 366.071 0 256 0z"/><path d="M15.923 166.957C5.633 194.69 0 224.686 0 256s5.633 61.31 15.923 89.043h480.155C506.368 317.31 512 287.314 512 256s-5.632-61.31-15.923-89.043H15.923z" fill="#d80027"/></svg>';
            $name = "Deutsch";
            break;
        case "ru":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#f0f0f0"/><path d="M496.077 345.043C506.368 317.31 512 287.314 512 256s-5.632-61.31-15.923-89.043H15.923C5.633 194.69 0 224.686 0 256s5.633 61.31 15.923 89.043L256 367.304l240.077-22.261z" fill="#0052b4"/><path d="M256 512c110.071 0 203.906-69.472 240.077-166.957H15.923C52.094 442.528 145.929 512 256 512z" fill="#d80027"/></svg>';
            $name = "Pусский";
            break;
        case "ar":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#6da544"/><g fill="#f0f0f0"><path d="M144.696 306.087c0 18.441 14.95 33.391 33.391 33.391h100.174c0 15.368 12.458 27.826 27.826 27.826h33.391c15.368 0 27.826-12.458 27.826-27.826v-33.391H144.696zM370.087 144.696v77.913c0 12.275-9.986 22.261-22.261 22.261v33.391c30.687 0 55.652-24.966 55.652-55.652v-77.913h-33.391zM130.783 222.609c0 12.275-9.986 22.261-22.261 22.261v33.391c30.687 0 55.652-24.966 55.652-55.652v-77.913h-33.391v77.913z"/><path d="M320 144.696h33.391v77.913H320zM269.913 189.217c0 3.069-2.497 5.565-5.565 5.565s-5.565-2.497-5.565-5.565v-44.522h-33.391v44.522c0 3.069-2.497 5.565-5.565 5.565s-5.565-2.497-5.565-5.565v-44.522H180.87v44.522c0 21.481 17.476 38.957 38.957 38.957a38.72 38.72 0 0022.261-7.016 38.726 38.726 0 0022.261 7.016c1.666 0 3.304-.117 4.915-.322-2.366 9.749-11.146 17.017-21.611 17.017v33.391c30.687 0 55.652-24.966 55.652-55.652v-77.913h-33.391v44.522z"/><path d="M180.87 244.87h50.087v33.391H180.87z"/></g></svg>';
            $name = "عربى";
            break;
        case "es":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 256c0 31.314 5.633 61.31 15.923 89.043L256 367.304l240.077-22.261C506.367 317.31 512 287.314 512 256s-5.633-61.31-15.923-89.043L256 144.696 15.923 166.957C5.633 194.69 0 224.686 0 256z" fill="#ffda44"/><g fill="#d80027"><path d="M496.077 166.957C459.906 69.473 366.071 0 256 0S52.094 69.473 15.923 166.957h480.154zM15.923 345.043C52.094 442.527 145.929 512 256 512s203.906-69.473 240.077-166.957H15.923z"/></g></svg>';
            $name = "Español";
            break;
        case "it":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#f0f0f0"/><path d="M512 256c0-110.071-69.472-203.906-166.957-240.077v480.155C442.528 459.906 512 366.071 512 256z" fill="#d80027"/><path d="M0 256c0 110.071 69.472 203.906 166.957 240.077V15.923C69.472 52.094 0 145.929 0 256z" fill="#6da544"/></svg>';
            $name = "Italiano";
            break;
        case "in":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#f0f0f0"/><path d="M256 0C154.506 0 66.81 59.065 25.402 144.696h461.195C445.19 59.065 357.493 0 256 0z" fill="#ff9811"/><path d="M256 512c101.493 0 189.19-59.065 230.598-144.696H25.402C66.81 452.935 154.506 512 256 512z" fill="#6da544"/><circle cx="256" cy="256" r="89.043" fill="#0052b4"/><circle cx="256" cy="256" r="55.652" fill="#f0f0f0"/><path fill="#0052b4" d="M256 187.326l17.169 38.938 42.304-4.601L290.337 256l25.136 34.337-42.304-4.601L256 324.674l-17.169-38.938-42.304 4.6L221.663 256l-25.136-34.337 42.304 4.601z"/></svg>';
            $name = "हिन्दी";
            break;
        case "pt":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M0 256c0 110.07 69.472 203.905 166.955 240.076l22.262-240.077-22.262-240.076C69.472 52.095 0 145.929 0 256z" fill="#6da544"/><path d="M512 256C512 114.616 397.384 0 256 0c-31.314 0-61.311 5.633-89.045 15.923v480.154C194.689 506.368 224.686 512 256 512c141.384 0 256-114.616 256-256z" fill="#d80027"/><circle cx="166.957" cy="256" r="89.043" fill="#ffda44"/><path d="M116.87 211.478v55.652c0 27.662 22.424 50.087 50.087 50.087s50.087-22.424 50.087-50.087v-55.652H116.87z" fill="#d80027"/><path d="M166.957 283.826c-9.206 0-16.696-7.49-16.696-16.696v-22.26h33.391v22.261c0 9.205-7.49 16.695-16.695 16.695z" fill="#f0f0f0"/></svg>';
            $name = "Português";
            break;
        case "cz":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#f0f0f0"/><path d="M233.739 256S75.13 437.055 74.98 437.019C121.306 483.346 185.307 512 256 512c141.384 0 256-114.616 256-256H233.739z" fill="#d80027"/><path d="M74.98 74.98c-99.974 99.974-99.974 262.065 0 362.04L256 256 74.98 74.98z" fill="#0052b4"/></svg>';
            $name = "Čeština";
            break;
        case "nl":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#f0f0f0"/><path d="M256 0C145.929 0 52.094 69.472 15.923 166.957h480.155C459.906 69.472 366.071 0 256 0z" fill="#a2001d"/><path d="M256 512c110.071 0 203.906-69.472 240.077-166.957H15.923C52.094 442.528 145.929 512 256 512z" fill="#0052b4"/></svg>';
            $name = "Nederlands";
            break;
        case "ur":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><g fill="#f0f0f0"><circle cx="256" cy="256" r="256"/><path d="M0 256c0 97.035 53.989 181.454 133.565 224.873V31.127C53.989 74.546 0 158.965 0 256z"/></g><path d="M256 0c-44.35 0-86.064 11.283-122.435 31.127v449.745C169.936 500.717 211.65 512 256 512c141.384 0 256-114.616 256-256S397.384 0 256 0z" fill="#496e2d"/><g fill="#f0f0f0"><path d="M365.453 298.337c-32.387 23.401-77.613 16.117-101.013-16.269-23.402-32.388-16.117-77.613 16.27-101.013 10.098-7.296 21.444-11.609 32.987-13.108-22.207-3.321-45.682 1.683-65.319 15.872-39.86 28.802-48.827 84.463-20.026 124.325 28.801 39.859 84.463 48.827 124.325 20.023 19.639-14.189 31.76-34.902 35.578-57.031-5.046 10.486-12.703 19.904-22.802 27.201zM364.066 166.959l18.244 19.661 24.336-11.272-13.063 23.424 18.243 19.663-26.316-5.185-13.062 23.426-3.201-26.63-26.316-5.185 24.337-11.272z"/></g></svg>';
            $name = "اردو";
            break;
        case "fr":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#f0f0f0"/><path d="M512 256c0-110.071-69.472-203.906-166.957-240.077v480.155C442.528 459.906 512 366.071 512 256z" fill="#d80027"/><path d="M0 256c0 110.071 69.473 203.906 166.957 240.077V15.923C69.473 52.094 0 145.929 0 256z" fill="#0052b4"/></svg>';
            $name = "Français";
            break;
        case "vn":
            $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><circle cx="256" cy="256" r="256" fill="#d80027"/><path fill="#ffda44" d="M256 133.565l27.628 85.029h89.405l-72.331 52.55 27.628 85.03L256 303.623l-72.33 52.551 27.628-85.03-72.33-52.55h89.404z"/></svg>';
            $name = "Tiếng Việt";
            break;
        default:
            $icon = '';
            $name = strtoupper($country_code);
            break;
    }
    return array("name" => $name, "icon" => $icon);
}

function list_languages($website_url = "")
{
    foreach (glob(__DIR__ . "/../../language/*.php") as $filename) {
        if (basename($filename) != "index.php") {
            $language = str_replace(".php", null, basename($filename));
            if (language_exists($language) === true) {
                $language_info = get_language_info($language);
                $flag = sprintf('<span class="country-flag mr-1 mt-1">%s</span>', $language_info["icon"]);
                printf('<a class="dropdown-item" href="%s?lang=%s">%s %s</a>', $website_url, $language, $flag, $language_info["name"]);
            }
        }
    }
}

function href_tags($website_url)
{
    foreach (glob(__DIR__ . "/../../language/*.php") as $filename) {
        if (basename($filename) != "index.php") {
            $language = str_replace(".php", null, basename($filename));
            if (language_exists($language) === true) {
                printf('<link rel="alternate" hreflang="%s" href="%s/?lang=%s"/>%s', $language, $website_url, $language, "\n");
            }
        }
    }
}

function get_supported_websites()
{
    // Fill slug and text values for change default ones
    $websites = array(
        array("name" => "9gag", "color" => "#000000", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "akillitv", "color" => "#3e3e3e", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "bandcamp", "color" => "#21759b", "slug" => "", "text" => "", "type" => "music"),
        array("name" => "bitchute", "color" => "#ef4137", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "blogger", "color" => "#fc4f08", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "break", "color" => "#b92b27", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "buzzfeed", "color" => "#df2029", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "dailymotion", "color" => "#0077b5", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "douyin", "color" => "#131418", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "espn", "color" => "#df2029", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "facebook", "color" => "#3b5998", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "febspot", "color" => "#f02730", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "flickr", "color" => "#ff0084", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "gaana", "color" => "#e72c30", "slug" => "", "text" => "", "type" => "music"),
        array("name" => "imdb", "color" => "#e8c700", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "imgur", "color" => "#02b875", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "instagram", "color" => "#e4405f", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "izlesene", "color" => "#ff6600", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "kwai", "color" => "#ff9000", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "likee", "color" => "#be3cfa", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "linkedin", "color" => "#0e76a8", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "liveleak", "color" => "#dd4b39", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "mashable", "color" => "#0084ff", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "odnoklassniki", "color" => "#f57d00", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "periscope", "color" => "#3fa4c4", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "pinterest", "color" => "#bf1f24", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "reddit", "color" => "#ff4301", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "rumble", "color" => "#74a642", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "soundcloud", "color" => "#ff3300", "slug" => "", "text" => "", "type" => "music"),
        array("name" => "streamable", "color" => "#2c2c2c", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "ted", "color" => "#e62b1e", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "tiktok", "color" => "#131418", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "tumblr", "color" => "#32506d", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "twitch", "color" => "#6441a5", "slug" => "", "text" => "", "type" => "clip"),
        array("name" => "twitter", "color" => "#00aced", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "vimeo", "color" => "#1ab7ea", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "vk", "color" => "#4a76a8", "slug" => "", "text" => "", "type" => "video"),
        array("name" => "youtube", "color" => "#d82624", "slug" => "", "text" => "", "type" => "video"),
    );
    return $websites;
}