<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <section>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-xl">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavBar" aria-controls="mainNavBar" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list"></i>
                </button>

                <div class="collapse navbar-collapse" id="mainNavBar">
                    <ul class="navbar-nav w-100">
                        <li class="nav-item">
                            <a class="nav-link" :href="route('dashboard')">Dashboard</a>
                        </li>

                        <li class="nav-item dropdown ms-auto">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ $page.props.auth.user.name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" :href="route('profile.edit')">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><Link class="dropdown-item" :href="route('logout')" method="post" as="button">Log Out</Link></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <div class="container-xl">
            <header class="my-4" v-if="$slots.header">
                <slot name="header" />
            </header>
        </div>

        <!-- Page Content -->
        <main class="container-xl">
            <slot />
        </main>
    </section>
</template>
