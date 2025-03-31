<template>
    <li class="post-item" >
        <div class="image-con">
            <img :src="post.image" alt="" v-on:click="viewPost()">
            <font-awesome-icon v-on:click="deletePost(post.id)" class="bin" v-if="post.username === this.$page.props.auth.user.username" :icon="['fas', 'trash-can']" />
        </div>
        <div class="post-title-con">
            <Link 
                v-if="post.username"
                :href="'/profile/' + post.username"
                method="get"
                class="post-username"
            >{{ post.username }}</Link>
            <h3>{{ post.title }}</h3>
        </div>
        <LikeButton :post="post"/>
    </li>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import LikeButton from './LikeButton.vue';
import axios from 'axios';

export default {
    components: {
        Link,
        LikeButton
    },
    props: {
        post: Object,
    },
    methods: {
        viewPost() {
            if(window.innerWidth > 640) {
                this.$emit('viewPost', this.post);
            }
        },
        deletePost(postId) {
        axios.delete(`/delete/${postId}`)
            .then(response => {
                console.log(response.data.message); 
                this.$emit('deletedPost', postId);
            })
            .catch(error => {
                console.error('There was an error deleting the post:', error);
            });
        }
    },
}
</script>

<style>
    .post-item {
        position: relative;
        .post-title-con {
            display: flex;
            gap: 10px;
            font-size: 16px;
            .post-username {
                color: var(--link-colour);
                background-color: #fff;
                padding: 0px 10px;
                font-weight: 600;
            }
        }
        .image-con {
            position: relative;
            .bin {
                position: absolute;
                padding: 4px;
                bottom: 0px;
                right: 0px;
            }
            @media (hover:hover) {
                .bin:hover {
                    color: var(--link-colour);
                }
            }
            img {
                aspect-ratio: 1/1;
                max-width: 400px;
                max-height: 400px;
                width: 100%;
                object-fit: cover;
            }
        }
    }
</style>