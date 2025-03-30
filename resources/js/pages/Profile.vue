<template>
    <Link 
            v-if="$page.props.auth.user"
            href="/dashboard"
            method="get"
            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
            Home
        </Link>
    <Link 
            v-if="$page.props.auth.user.username === user.username"
            href="/settings"
            method="get"
            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
            Settings
        </Link>
    <div class="page-wrap">
        <div>
        <h1 v-if="user.name.length < 0">{{ user.name }}'s Profile</h1>
        <p>{{ user.username }}</p>
        <button
            v-if="$page.props.auth.user.username !== user.username"
            @click="toggleFollow"
            :class="{ 'bg-blue-500': following, 'bg-gray-500': !following }"
            >
            {{ following ? 'Unfollow' : 'Follow' }}
        </button>
        </div>
        <Followers :count="followerCount" :followers="followers"/>
        <div class="users-posts">
            <h1>Posts</h1>
            <ul class="posts-container" v-if="posts.length > 0">
                <PostItem v-for="post in storedPosts" :key="post.id" :post="post" @viewPost="viewPost"/>
            </ul>
            <div v-if="posts.length === 0">
                <p>No posts available.</p>
            </div>
        </div>
    </div>

    <FullScreenPost v-if="clickedPost !== null" :post="clickedPost" @closePost="closePost" @deletedPost="deletedPost"/>
    
</template>
  
<script>
import axios from 'axios';
import { Head, Link } from '@inertiajs/vue3';
import PostItem from '@/components/custom/PostItem.vue';
import FullScreenPost from '@/components/custom/FullScreenPost.vue';
import Followers from '@/components/custom/Followers.vue';


  export default {
    props: {
      user: Object,
      posts: Object,
      isFollowing: Boolean,
      followers: Array,
      followerCount: Number,
      authUser: Object,
    },
    data() {
        return {
            following: this.isFollowing,
            clickedPost: null,
            storedPosts: [],
        }
    },
    components: {
        Link,
        PostItem,
        FullScreenPost,
        Followers,
    },
    created() {
        this.storedPosts = this.posts;
    },
    methods: {
        toggleFollow() {
            if (this.following) {
                // Unfollow user
                axios.post(`/profile/${this.user.username}/unfollow`)
                .then(() => {
                    this.following = false;
                })
                .catch(error => {
                    console.error('Error unfollowing user:', error);
                });
            } else {
                // Follow user
                axios.post(`/profile/${this.user.username}/follow`)
                .then(() => {
                    this.following = true;
                })
                .catch(error => {
                    console.error('Error following user:', error);
                });
            }
        },
        viewPost(emittedPost) {
            this.clickedPost = emittedPost;
        },
        closePost() {
            this.clickedPost = null;
        },
        deletedPost(postId) {
            this.storedPosts = this.storedPosts.filter(post => post.id !== postId);
            this.clickedPost = null;
        },
    }
  };
  </script>

<style scoped>
.page-wrap {
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    align-items: center;
    .posts-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        max-width: 1200px;
        gap: 20px;
    }
}
</style>