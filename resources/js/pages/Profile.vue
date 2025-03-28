<template>
    <Link 
            v-if="$page.props.auth.user"
            href="/dashboard"
            method="get"
            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
            Home
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
        <div class="users-posts">
            <h1>Posts</h1>
            <ul class="posts-container" v-if="posts.length > 0">
                <PostItem v-for="post in posts" :key="post.id" :post="post"/>
            </ul>
            <div v-if="posts.length === 0">
                <p>No posts available.</p>
            </div>
        </div>
    </div>
    
</template>
  
<script>
import axios from 'axios';
import { Head, Link } from '@inertiajs/vue3';
import PostItem from '@/components/custom/PostItem.vue';


  export default {
    props: {
      user: Object,
      posts: Object,
      isFollowing: Boolean,
      authUser: Object,
    },
    data() {
        return {
            following: this.isFollowing,
        }
    },
    components: {
        Link,
        PostItem,
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
        }
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