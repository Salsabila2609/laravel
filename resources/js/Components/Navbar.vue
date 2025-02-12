<template>
  <nav :class="['navbar', { 'navbar-fixed': isMenuOpen }]" style="font-size: 15px;">
    <div class="container mx-auto px-4">
      <!-- Rest of the navbar content remains the same -->
      <div class="flex justify-between items-center py-4">
        <!-- Logo -->
        <a href="/" class="block">
          <img src="/img/Logo.png" alt="Logo" class="h-12 w-auto">
        </a>
        <!-- Hamburger Menu -->
        <div class="lg:hidden flex items-center">
          <button @click="toggleMenu" class="text-gray-700 hover:text-[#D4A017]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
          </button>
        </div>

        <!-- Desktop Nav Links (unchanged) -->
        <div class="hidden lg:flex items-center space-x-7">
            <!-- Beranda -->
            <Link href="/" class="text-lg font-light text-gray-700 hover:text-[#D4A017]">Beranda</Link>
  
            <!-- Profil -->
            <div class="relative group">
             <button class="text-lg font-light text-gray-700 hover:text-[#D4A017] flex items-center">Profil
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                  <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <div class="absolute left-0 hidden mt-1 w-48 bg-white shadow-lg rounded-md group-hover:block">
                <Link href="/PejabatDaerah" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Pejabat Daerah</Link>
                <div @mouseover="showDropdown('dropdown1')" @mouseleave="hideDropdown('dropdown1')" class="relative">
                  <button class="group flex items-center justify-between block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1"> Link Website
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                      <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <div v-if="dropdowns.dropdown1" class="absolute top-0 left-full ml-2 w-64 bg-white shadow-lg rounded-md">
                    <Link href="/OPD" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">Organisasi Perangkat Daerah (OPD)</Link>
                    <Link href="/Kecamatan" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1e">Kecamatan Se-Kabupaten Madiun</Link>
                  </div>
                </div>
                <div @mouseover="showDropdown('dropdown2')" @mouseleave="hideDropdown('dropdown2')" class="relative mt-1">
                  <button class="group flex items-center justify-between block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1"> Profil
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                      <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <div v-if="dropdowns.dropdown2" class="absolute top-0 left-full ml-2 w-48 bg-white shadow-lg rounded-md">
                    <Link href="/LambangDaerah" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Lambang Daerah</Link>
                    <Link href="/Sejarah" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Sejarah</Link>
                    <Link href="/VisiMisi" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">Visi & Misi</Link>
                  </div>
                </div>
              </div>
            </div>
  
            <!-- Berita -->
            <Link href="/berita" class="text-lg font-light text-gray-700 hover:text-[#D4A017]">Berita</Link>
  
  
            <!-- Informasi Publik -->
            <div class="relative group">
              <button class="text-lg font-light text-gray-700 hover:text-[#D4A017] flex items-center">Informasi Publik
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                  <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button> 
              <div class="absolute left-0 hidden mt-1 w-48 bg-white shadow-lg rounded-md group-hover:block">
                <div @mouseover="showDropdown('dropdown1')" @mouseleave="hideDropdown('dropdown1')" class="relative">
                  <button class="group text-left flex items-center justify-between block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1"> Laporan Keuangan
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 22 22" fill="currentColor" stroke="currentColor" stroke-width="1">
                      <path fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <div v-if="dropdowns.dropdown1" class="absolute top-0 left-full ml-2 w-48 bg-white shadow-lg rounded-md">
                    <Link href="/LapKeu2025" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">2025</Link>
                    <Link href="/LapKeu2024" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">2024</Link>
                    <Link href="/LapKeu2023" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">2023</Link>
                    <Link href="/LapKeu2022" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">2022</Link>
                    <Link href="/LapKeu2021" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">2021</Link>
                    <Link href="/LapKeu2020" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">2018-2020</Link>
                  </div>
                </div>
                <Link href="/PeraturanBupati" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">Peraturan Bupati</Link>
                <Link href="/DataStatistik" class="block px-4 py-2 text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017]  hover:rounded-md hover:shadow w-full px-2 py-1">Data Statistik</Link>
              </div>
            </div>
  
            <!-- PPID -->
            <Link href="/PPID" class="text-lg font-light text-gray-700 hover:text-[#D4A017]">PPID</Link>
  
            <!-- Kontak -->
            <Link href="/Kontak" class="text-lg font-light text-gray-700 hover:text-[#D4A017]">Kontak Kami</Link>
          </div>
        </div>
  
      <!-- Mobile Menu with Updated Positioning -->
      <div v-if="isMenuOpen" class="mobile-menu lg:hidden">
        <div class="flex flex-col space-y-4 py-4 px-4 bg-white shadow-lg rounded-md">

            <!-- Beranda -->
            <Link href="/" class="text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1 ">Beranda</Link>
  
            <!-- Profil -->
            <div>
              <button @click="toggleDropdown('profil')" class="flex items-center justify-between text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">
                Profil
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                  <path v-if="isDropdownOpen('profil')" fill="none" stroke="currentColor" stroke-width="3" fill-rule="evenodd" d="M6 18L18 6M6 6l12 12" clip-rule="evenodd" />
                  <path v-else fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <div v-if="isDropdownOpen('profil')" class="mt-2 pl-4 space-y-2">
                <Link href="/PejabatDaerah" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Pejabat Daerah</Link>
                <div>
                  <button @click="toggleDropdown('linkWebsite')" class="flex items-center justify-between text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">
                    Link Website
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                      <path v-if="isDropdownOpen('linkWebsite')" fill="none" stroke="currentColor" stroke-width="3" fill-rule="evenodd" d="M6 18L18 6M6 6l12 12" clip-rule="evenodd" />
                      <path v-else fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <div v-if="isDropdownOpen('linkWebsite')" class="mt-2 pl-4 space-y-2">
                    <Link href="/OPD" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Organisasi Perangkat Daerah (OPD)</Link>
                    <Link href="/Kecamatan" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Kecamatan Se-Kabupaten Madiun</Link>
                  </div>
                </div>
                <div>
                  <button @click="toggleDropdown('profilKabupaten')" class="flex items-center justify-between text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">
                    Profil Kabupaten
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                      <path v-if="isDropdownOpen('profilKabupaten')" fill="none" stroke="currentColor" stroke-width="1" fill-rule="evenodd" d="M6 18L18 6M6 6l12 12" clip-rule="evenodd" />
                      <path v-else fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                  <div v-if="isDropdownOpen('profilKabupaten')" class="mt-2 pl-4 space-y-2">
                    <Link href="/LambangDaerah" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Lambang Daerah</Link>
                    <Link href="/Sejarah" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Sejarah</Link>
                    <Link href="/VisiMisi" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Visi & Misi</Link>
                  </div>
                </div>
              </div>
            </div>

  
            <!-- PPID -->
            <Link href="/PPID" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">PPID</Link>
  
            <!-- Berita -->
            <Link href="/berita" class="block text-lg font-light text-gray-700 hover:text-[#ffffff] hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Berita</Link>
  
            <!-- Informasi Publik -->
            <div>
              <button @click="toggleDropdown('informasiPublik')" class="flex items-center justify-between text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">
                Informasi Publik
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                  <path v-if="isDropdownOpen('informasiPublik')" fill="none" stroke="currentColor" stroke-width="1" fill-rule="evenodd" d="M6 18L18 6M6 6l12 12" clip-rule="evenodd" /> 
                  <path v-else fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" /> 
                </svg>
              </button>
              <div v-if="isDropdownOpen('informasiPublik')" class="mt-2 pl-4 space-y-2">
                <div>
                  <button @click="toggleDropdown('laporanKeuangan')" class="flex items-center justify-between text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">
                      Laporan Keuangan Daerah
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="1">
                        <path v-if="isDropdownOpen('laporanKeuangan')" fill="none" stroke="currentColor" stroke-width="1" fill-rule="evenodd" d="M6 18L18 6M6 6l12 12" clip-rule="evenodd" /> 
                        <path v-else fill-rule="evenodd" d="M5.293 9.293a1 1 0 011.414 0L12 14.586l5.293-5.293a1 1 0 111.414 1.414l-6 6a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414z" clip-rule="evenodd" /> 
                      </svg>
                    </button><div v-if="isDropdownOpen('laporanKeuangan')" class="mt-2 pl-4 space-y-2">
                    <Link href="/LapKeu2025" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">2025</Link>
                    <Link href="/LapKeu2024" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">2024</Link>
                    <Link href="/LapKeu2023" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">2023</Link>
                    <Link href="/LapKeu2022" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">2022</Link>
                    <Link href="/LapKeu2021" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">2021</Link>
                    <Link href="/LapKeu2020" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">2018-2020</Link>
                  </div>
                </div>
  
                <Link href="/PeraturanBupati" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Peraturan Bupati</Link>
                <Link href="/DataStatistik" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Data Statistik</Link>
              </div>
            </div>
  
            <!-- PPID -->
            <Link href="/PPID" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">PPID</Link>
  
            <!-- Kontak -->
            <Link href="/Kontak" class="block text-lg font-light text-gray-700 hover:text-[#ffffff]  hover:bg-[#D4A017] hover:rounded-md hover:shadow w-full px-2 py-1">Kontak Kami</Link>
          </div>
        </div>
      </div>
    </nav>
  </template>
  
<script>
import { Link } from '@inertiajs/vue3';

export default {
  components: {
    Link,
  },
  data() {
    return {
      isMenuOpen: false,
      dropdowns: {
        dropdown1: false,
        dropdown2: false,
        profil: false,
        linkWebsite: false,
        profilKabupaten: false,
        berita: false,
        informasiPublik: false,
        laporanKeuangan: false,
      },
      isMobileView: false,
    };
  },
  methods: {
    toggleMenu() {
      this.isMenuOpen = !this.isMenuOpen;
    },
    showDropdown(dropdownKey) {
      this.dropdowns[dropdownKey] = true;
    },
    hideDropdown(dropdownKey) {
      this.dropdowns[dropdownKey] = false;
    },
    toggleDropdown(dropdownKey) {
      this.dropdowns[dropdownKey] = !this.dropdowns[dropdownKey];
    },
    isDropdownOpen(dropdownKey) {
      return this.dropdowns[dropdownKey];
    },
    checkScreenSize() {
      this.isMobileView = window.innerWidth <= 1024;
      if (!this.isMobileView) {
        this.isMenuOpen = false;
        this.dropdowns = {};
      }
    }
  },
  mounted() {
    this.checkScreenSize();
    window.addEventListener('resize', this.checkScreenSize);
  },
  beforeDestroy() {
    window.removeEventListener('resize', this.checkScreenSize);
  },
};
</script>

<style scoped>
.navbar {
  position: relative;
  width: 100%;
  z-index: 50;
  background-color: white;
}

.navbar-fixed {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
}

.navbar-fixed .container {
  position: relative;
}

.mobile-menu {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background-color: white;
  z-index: 49;
  width: 100%;
}

.navbar-fixed .mobile-menu {
  position: fixed;
  top: 80px; /* Adjust based on your navbar height */
}

/* Dropdown styles */
.absolute {
  display: none;
  visibility: hidden;
  opacity: 0;
  position: absolute;
  margin-top: 0;
  left: 0;
  transition: opacity 0.3s ease, visibility 0s 0.3s;
}

.relative:hover .absolute {
  display: block;
  visibility: visible;
  opacity: 1;
  transition: opacity 0.3s ease;
}

.relative .absolute.left-full {
  top: 0;
  left: 100%;
  margin-left: 0rem;
}
</style>