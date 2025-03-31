<template>
    <div class="post-like">
        <button @click="toggleLike(post.id)">
            <font-awesome-icon class="heart" v-if="!dataIsLiked" :icon="['far', 'heart']" />
            <font-awesome-icon class="heart" v-if="dataIsLiked" :icon="['fas', 'heart']" />
            <p>{{ dataLikesCount }}</p>
        </button>
    </div>
</template>

<script>
import axios from 'axios';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

export default {
    props: {
        post: Object,
        isLiked: Boolean,
        likesCount: Number,
    },
    components: {
        FontAwesomeIcon,
    },
    data() {
        return {
            dataIsLiked: false,    
            dataLikesCount: 0,
        };
    },
    created() {
        this.getLikes(this.post.id)
    },
    methods: {
        getLikes(postId) {
            axios.post(`/posts/${postId}/get-like`)
                .then(response => {
                    this.dataIsLiked = response.data.is_liked;
                    this.dataLikesCount = response.data.likes_count;
                })
                .catch(error => {
                });
        },
        toggleLike(postId) {
            axios.post(`/posts/${postId}/toggle-like`)
                .then(response => {
                    this.dataIsLiked = !response.data.is_liked;
                    this.dataLikesCount = response.data.likes_count;
                })
                .catch(error => {
                    console.error('Error liking post:', error);
                });
        },
    }
}
</script>

<style>
    .post-like {
        position: absolute;
        top: 0px;
        right: 0px;
        button {
            background-color: var(--white);
            padding: 8px 8px 14px 14px;
            color: var(--link-colour);
            border-bottom-left-radius: 100%;
            display: flex;
            gap: 10px;
            .heart {
                font-size: 20px;
            }
        }
    }
</style>