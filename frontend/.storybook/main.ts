import type { StorybookConfig } from '@storybook/vue3-vite';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

const config: StorybookConfig = {
  stories: ['../components/**/*.stories.@(js|jsx|ts|tsx|mdx)'],
  addons: [
    '@storybook/addon-essentials',
    '@storybook/addon-docs'
  ],
  framework: {
    name: '@storybook/vue3-vite',
    options: {}
  },
  viteFinal: (config) => {
    // Configuration Vite pour Nuxt
    config.plugins = config.plugins || [];
    config.plugins.push(vue());
    
    config.resolve = config.resolve || {};
    config.resolve.alias = {
      ...config.resolve.alias,
      '~': new URL('../', import.meta.url).pathname,
      '@': new URL('../', import.meta.url).pathname,
    };
    return config;
  }
};

export default config;