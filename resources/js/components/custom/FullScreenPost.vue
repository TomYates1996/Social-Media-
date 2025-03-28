<template>
    <div class="neat-wrapper">
        <li class="detail-post-item">
            <img :src="post.image" alt="">
            <div class="text-cont">
                <button class="close" v-on:click="closePost()">X</button>
                <h3>{{ post.title }}</h3>
                <p>{{ post.body }}</p>
                <Link 
                    :href="'/profile/' + post.username"
                    method="get"
                    class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
                >{{ post.username }}</Link>
            </div>
        </li>
    </div>
</template>
<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
    props: {
        post: Object,
    },
    methods: {
        closePost() {
            this.$emit('closePost');
        },
    },
}
</script>

<style scoped>
    .neat-wrapper {
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        padding: 40px 30px;
        justify-content: center;
        align-items: center;
        display: flex;
        .detail-post-item {
            background-color: #00e1ac;;
            width: 100%;
            max-width: 900px;
            display: flex;
            align-items: stretch;
            justify-content: center;
            padding: 20px;
            border-radius: 20px;
            color: #000000;
            gap: 20px;
            img {
                aspect-ratio: 1/1;
                width: 460px;
                height: 460px;
            }
            .text-cont {
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: flex-start;
                gap: 14px;
                height: 100%;
                .close {
                    position: relative;
                    margin-left: auto;
                    background-color: #000000;
                    height: 30px;
                    width: 30px;
                    color: #ffffff;
                    border-radius: 4px;
                }
                h3 {
                    font-size: 30px;
                    font-weight: 700;
                }
            }
        }
    }
</style>