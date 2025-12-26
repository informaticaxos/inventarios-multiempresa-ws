import React from 'react';
import ProductList from '../components/products/ProductList';
import Header from '../components/common/Header';
import Sidebar from '../components/common/Sidebar';
import { Box, Toolbar } from '@mui/material';

const Products = () => {
  return (
    <Box sx={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <Header />
      <Box sx={{ display: 'flex', flexGrow: 1 }}>
        <Sidebar />
        <Box component="main" sx={{ flexGrow: 1, p: 3, overflow: 'auto' }}>
          <Toolbar />
          <ProductList />
        </Box>
      </Box>
    </Box>
  );
};

export default Products;