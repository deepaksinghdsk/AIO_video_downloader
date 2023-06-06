$(document).ready(function () {
    var isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    if (isMobile) {
        document.getElementById('download_area').classList.add("mt-0");
    }
    var executed = false;
    var get_location = function (href) {
        var l = document.createElement("a");
        l.href = href;
        return l;
    };

    $(window).bind('hashchange', function () {
        url();
    });

    placeholder_text();

    var input = document.getElementById("url");
    if (input != null) {
        input.addEventListener("keyup", function (event) {
            event.preventDefault();
            if (event.keyCode === 13) {
                document.getElementById("send").click();
            }
        });
    }
    url();


    document.getElementById("paste").addEventListener("click", (e) => {
        var pasteBtn = document.getElementById("paste");
        var input = document.getElementById("url")
        if (pasteBtn.innerHTML === '<i class="far fa-trash-alt fa-lg"></i>') {
            input.value = "";
            pasteBtn.innerHTML = '<i class="far fa-clipboard fa-lg"></i>';
        } else {
            navigator.clipboard.readText().then(clipText =>
                    input.value = clipText,
                pasteBtn.innerHTML = '<i class="far fa-trash-alt fa-lg"></i>');
        }
    });

    $(document).keypress(function (e) {
        if (e.which == 13) {
            var url = $('#url').val();
            if (url !== "") {
                executed = true;
                document.getElementById("send").html('<i class="fas fa-circle-notch fa-spin"></i>');
                document.getElementById("send").disabled = true;
                var token = $('#token').val();
                submit(url, token);
                remove_hash();
                window.location.replace("#url=" + url);
            }
            e.preventDefault();
        }
    });

    function send(e) {
        var url = $('#url').val();
        if (url !== "") {
            executed = true;
            $('#send').html('<i class="fas fa-circle-notch fa-spin"></i>');
            document.getElementById("send").disabled = true;
            var token = $('#token').val();
            submit(url, token);
            remove_hash();
            window.location.replace("#url=" + url);
        }
        e.preventDefault();
    }

    $('#send').click(function (e) {
        send(e);
    });

    //window.send = send;

    $('#show-all').click(function (e) {
        var elements = document.querySelectorAll('.btn-dash');
        for (var i = 0; i < elements.length; i++) {
            if (elements[i].style.display === "none") {
                elements[i].style.display = "";
                document.getElementById("show-all").className = "btn btn-sm btn-info btn-block";
            } else {
                elements[i].style.display = "none";
                document.getElementById("show-all").className = "btn btn-sm btn-dark btn-block";
            }

        }
        e.preventDefault();
    });

    function sleep(milliseconds) {
        var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
            if ((new Date().getTime() - start) > milliseconds) {
                break;
            }
        }
    }

    function url() {
        if (window.location.href.indexOf("#url=") > -1 && executed === false) {
            var url = window.location.href.match(new RegExp("#url=(.+)", ""))[1];
            var token = $('#token').val();
            $('#url').val(url);
            document.getElementById('send').scrollIntoView();
            //sleep(2000);
            /*
            while (token !== "" && url !== "") {
                $('#send').html('<i class="fas fa-circle-notch fa-spin"></i>');
                document.getElementById("send").disabled = true;
                document.getElementById('links').scrollIntoView();
                submit(url, token);
            }
            */
        }
    }

    function _template(data) {
        if (data !== "error" && data.title !== "") {
            var links_html_code = "";
            var audio_links_html_code = "";
            var links = data.links;
            var audio_link = 0;
            var video_link = 0;
            var i = 0;
            links.forEach(function (link) {
                if (link.url !== null) {
                    var downloadTitle = btoa(unescape(encodeURIComponent(data.title)));
                    link.url = btoa(unescape(encodeURIComponent(link.url)));
                    var link_row = "";
                    switch (true) {
                        case(link.type === "m4a" || link.type === "mp3" || link.quality.includes("kbps")):
                            link_row = '<a href="{{url}}" class="btn btn-success btn-sq btn-dl" target="_blank"><span class="align-middle"><i class="fas fa-headphones"></i><br>{{quality}}<br>{{type}}<br>({{size}})</span></a>';
                            link_row = link_row.replace(new RegExp("{{quality}}", "g"), link.quality);
                            link_row = link_row.replace(new RegExp("{{type}}", "g"), link.type);
                            link_row = link_row.replace(new RegExp("{{size}}", "g"), link.size);
                            link_row = link_row.replace(new RegExp("{{url}}", "g"), "dl.php?source=" + data.source + "&dl=" + btoa(i));
                            audio_links_html_code = audio_links_html_code.concat(link_row);
                            audio_link++;
                            i++;
                            break;
                        case((data.source === "youtube" && link.mute === true) || (link.type === "mp4" && link.mute === true)):
                            link_row = '<a href="{{url}}" class="btn btn-warning btn-sq btn-dl btn-dash" target="_blank"><span class="align-middle"><i class="fas fa-volume-mute fa-lg"></i><br>{{quality}}<br>{{type}}<br>({{size}})</span></a>';
                            link_row = link_row.replace(new RegExp("{{quality}}", "g"), link.quality);
                            link_row = link_row.replace(new RegExp("{{type}}", "g"), link.type);
                            link_row = link_row.replace(new RegExp("{{size}}", "g"), link.size);
                            link_row = link_row.replace(new RegExp("{{url}}", "g"), "dl.php?source=" + data.source + "&dl=" + btoa(i));
                            links_html_code = links_html_code.concat(link_row);
                            video_link++;
                            i++;
                            break;
                        case(link.type === "jpg"):
                            link_row = '<a href="{{url}}" class="btn btn-primary btn-sq btn-dl" target="_blank"><span class="align-middle"><i class="fas fa-images"></i><br>{{quality}}<br>{{type}}<br>({{size}})</span></a>';
                            link_row = link_row.replace(new RegExp("{{quality}}", "g"), link.quality);
                            link_row = link_row.replace(new RegExp("{{type}}", "g"), link.type);
                            link_row = link_row.replace(new RegExp("{{size}}", "g"), link.size);
                            link_row = link_row.replace(new RegExp("{{url}}", "g"), "dl.php?source=" + data.source + "&dl=" + btoa(i));
                            links_html_code = links_html_code.concat(link_row);
                            video_link++;
                            i++;
                            break;
                        default:
                            link_row = '<a href="{{url}}" class="btn btn-info btn-sq btn-dl" target="_blank"><span class="align-middle"><i class="fas fa-video"></i><br>{{quality}}<br>{{type}}<br>({{size}})</span></a>';
                            link_row = link_row.replace(new RegExp("{{quality}}", "g"), link.quality);
                            link_row = link_row.replace(new RegExp("{{type}}", "g"), link.type);
                            link_row = link_row.replace(new RegExp("{{size}}", "g"), link.size);
                            link_row = link_row.replace(new RegExp("{{url}}", "g"), "dl.php?source=" + data.source + "&dl=" + btoa(i));
                            links_html_code = links_html_code.concat(link_row);
                            video_link++;
                            i++;
                            break;
                    }
                }
            });
            var table_url = "";
            var template = "";
            switch (true) {
                case(audio_link > 0 && video_link === 0):
                    table_url = "template/material/downloads.php?audio=true";
                    break;
                case(audio_link > 0 && video_link > 0):
                    table_url = "template/material/downloads.php?video=true&audio=true";
                    break;
                default:
                    table_url = "template/material/downloads.php?video=true";
                    break;

            }
            var cacheTemplate = false;
            if (jQuery.isEmptyObject(localStorage.getItem(table_url)) === false && cacheTemplate) {
                template = localStorage.getItem(table_url);
            } else {
                $.ajax({
                    url: table_url,
                    async: false,
                    dataType: "html",
                    success: function (code) {
                        localStorage.setItem(table_url, code);
                    }
                });
                template = localStorage.getItem(table_url);
            }
            template = template.replace(new RegExp("{{video_title}}", "g"), data.title);
            template = template.replace(new RegExp("{{video_thumbnail}}", "g"), data.thumbnail);
            template = template.replace(new RegExp("{{video_duration}}", "g"), data.duration);
            var sharing_url = window.location.href.replace(new RegExp("#url=", "g"), "?u=");
            template = template.replace(new RegExp("{{facebook_share_link}}", "g"), encodeURI("https://www.facebook.com/sharer.php?u=" + sharing_url));
            template = template.replace(new RegExp("{{twitter_share_link}}", "g"), encodeURI("https://twitter.com/intent/tweet?url=" + sharing_url + "&text=Download " + data.title));
            template = template.replace(new RegExp("{{whatsapp_share_link}}", "g"), encodeURI("whatsapp://send?text=Download " + data.title + " " + sharing_url));
            template = template.replace(new RegExp("{{pinterest_share_link}}", "g"), encodeURI("http://pinterest.com/pin/create/link/?url=" + sharing_url));
            template = template.replace(new RegExp("{{tumblr_share_link}}", "g"), encodeURI("https://www.tumblr.com/widgets/share/tool?canonicalUrl=" + sharing_url + "&title=" + data.title));
            template = template.replace(new RegExp("{{reddit_share_link}}", "g"), encodeURI("https://reddit.com/submit?url=" + sharing_url + "&title=" + data.title));
            template = template.replace(new RegExp("{{qr_share_link}}", "g"), encodeURI("https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" + sharing_url));
            if (video_link > 0) {
                template = template.replace(new RegExp("{{video_links}}", "g"), links_html_code);
            }
            if (audio_link > 0) {
                template = template.replace(new RegExp("{{audio_links}}", "g"), audio_links_html_code);
            }
            $('#links').html(template);
            if (data.duration === undefined) {
                $('#video-details').remove();
            }
            if (data.source !== "youtube") {
                $('#show-all').remove();
            }
            $('.fa-spinner').remove();
            document.getElementById("send").disabled = false;
            var send = $("#send");
            send.empty();
            send.html('<i class="fas fa-download"></i>');
            $('#links').addClass('result');
            document.getElementById('download_area').scrollIntoView();
        } else {
            alert();
        }
    }

    function cache_valid(url) {
        if (typeof (Storage) !== "undefined") {
            if (jQuery.isEmptyObject(localStorage.getItem(url)) === false) {
                var json = JSON.parse(localStorage.getItem(url));
                var ago = Math.abs(new Date() - new Date(json["timestamp"])) / 36e5;
                ago = Math.floor(ago % 36e5 / 60000);
                if (ago > 1) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function submit(url, token) {
        var enable_cache = false;
        if (cache_valid(url) && enable_cache) {
            _template(JSON.parse(localStorage.getItem(url)));
        } else {
            $.post("system/action.php", {url: url, token: token}, function (data, status, xhr) {
                if (data !== "error" && data.title !== "" && xhr.status === 200) {
                    data["timestamp"] = new Date();
                    localStorage.setItem(url, JSON.stringify(data));
                    _template(data);
                } else {
                    alert();
                }
            })
        }
    }

    function remove_hash() {
        history.pushState("", document.title, window.location.pathname
            + window.location.search);
    }

    function alert() {
        document.getElementById('body').scrollIntoView();
        $('.fa-spinner').remove();
        document.getElementById("send").disabled = false;
        var send = $("#send");
        send.empty();
        send.html('<i class="fas fa-download"></i>');
        $("[data-toggle='popover']").popover('show');
        setTimeout(function () {
            $("[data-toggle='popover']").popover('hide');
        }, 5000);
    }

    function placeholder_text() {
        if (document.getElementById("url") != null) {
            var i = 0;
            var default_text = $('#url').attr('placeholder');
            var sources = [default_text, "YouTube", "Facebook", "Twitter", "Dailymotion", "Vimeo", "Instagram", "and more..."];
            setInterval(function () {
                if (sources[i] !== undefined) {
                    document.getElementById("url").placeholder = sources[i];
                }
                if (sources.length > i) {
                    i++;
                } else {
                    i = 0;
                }
            }, 750);
        }
    }
});