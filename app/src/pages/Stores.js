import React from 'react';
import StoreList from '../components/stores/StoreList';
import Header from '../components/common/Header';
import Sidebar from '../components/common/Sidebar';
import { Box, Toolbar } from '@mui/material';

const Stores = () => {
  return (
    <Box sx={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <Header />
      <Box sx={{ display: 'flex', flexGrow: 1 }}>
        <Sidebar />
        <Box component="main" sx={{ flexGrow: 1, p: 3, overflow: 'auto' }}>
          <Toolbar />
          <StoreList />
        </Box>
      </Box>
    </Box>
  );
};

export default Stores;