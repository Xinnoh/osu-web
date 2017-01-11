{{--
    Copyright 2015-2017 ppy Pty. Ltd.

    This file is part of osu!web. osu!web is distributed with the hope of
    attracting more community contributions to the core ecosystem of osu!.

    osu!web is free software: you can redistribute it and/or modify
    it under the terms of the Affero GNU General Public License version 3
    as published by the Free Software Foundation.

    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
    See the GNU Affero General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
--}}
Timeout.set(0, function () {
    $el = $(".js-forum-post[data-post-id={{ $post->post_id }}]")

    $toggle = $el.find(".delete-post-link");

    @if (Auth::user()->isAdmin() || Auth::user()->isGMT())
        $post = $el.find(".forum-post");

        @yield("action")

        $toggle.replaceWith({!! json_encode(render_to_string('forum.topics._post_hide_action', [
            'post' => $post,
        ])) !!});
    @else
        $el.css({
            minHeight: "0px",
            height: $el.css("height")
        }).slideUp(null, function () {
            return $el.remove();
        });
    @endif

    countDifference = action === "delete" ? -1 : 1;

    window.forum.setTotalPosts(window.forum.totalPosts() + countDifference);
    window.forum.setDeletedPosts(window.forum.deletedPosts() - countDifference);

    for (i = window.forum.posts.length - 1; i >= 0; i--) {
        post = window.forum.posts[i];

        originalPosition = forum.postPosition(post);

        if (originalPosition < {{ $post->topic->postPosition($post->post_id) }}) {
            break;
        }

        post.setAttribute("data-post-position", originalPosition + countDifference);
    }
});
