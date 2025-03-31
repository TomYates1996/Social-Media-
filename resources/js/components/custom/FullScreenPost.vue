<template>
    <div class="wrapper">
        <li class="detail-post-item">
            <font-awesome-icon class="close" v-on:click="closePost()" :icon="['fas', 'square-xmark']" />
            <div class="detail-left">
                <div class="image-con">
                    <img :src="post.image" alt="Post Image">
                    <LikeButton class="like-btn" :post="post"/>
                </div>
            </div>
            <div class="text-cont">
                <h3>{{ post.title }}</h3>
                <Link 
                    v-if="post.username"
                    :href="'/profile/' + post.username"
                    method="get"
                    class="post-username"
                    >{{ post.username }}</Link>
                    <Comments :postId="post.id" :comments="post.comments" />
                <button v-if="post.username === $page.props.auth.user.username" v-on:click="deletePost(post.id)">Delete</button>
            </div>
        </li>
    </div>
</template>
<script>
import { Link } from '@inertiajs/vue3';
import axios from 'axios';
import LikeButton from './LikeButton.vue';
import Comments from './Comments.vue';

export default {
    components: {
        Link,
        LikeButton,
        Comments,
    },
    props: {
        post: Object,
    },
    created() {
        console.log(this.post);
        
    },
    methods: {
        closePost() {
            this.$emit('closePost');
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

<style scoped>
    .wrapper {
        position: fixed;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        padding: 40px 30px;
        justify-content: center;
        align-items: center;
        display: flex;
        background-color: #0000009a;
        .detail-post-item {
            position: relative;
            background-color: var(--primary-colour);
            width: 100%;
            max-width: 1400px;
            height: 100%;
            display: flex;
            justify-content: center;
            padding: 20px;
            border-radius: 20px;
            color: var(--white);
            gap: 20px;
            .close {
                position: absolute;
                top: 0px;
                right: 0px;
                height: 40px;
                width: 40px;
                color: var(--secondary-colour);
                font-weight: 900;
            }
            .image-con {
                position: relative;
                .like-btn {
                    position: absolute;
                    top: 0px;
                    right: 0px;
                }
            }
            img {
                aspect-ratio: 1/1;
                width: 460px;
                height: 460px;
                object-fit: cover;
            }
            .text-cont {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
                gap: 14px;
                height: 100%;
                
                h3 {
                    font-size: 30px;
                    font-weight: 700;
                }
            }
        }
    }
</style>