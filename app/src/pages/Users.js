import React from 'react';
import UserList from '../components/users/UserList';
import Header from '../components/common/Header';
import Sidebar from '../components/common/Sidebar';
import { Box, Toolbar } from '@mui/material';

const Users = () => {
  return (
    <Box sx={{ display: 'flex', flexDirection: 'column', minHeight: '100vh' }}>
      <Header />
      <Box sx={{ display: 'flex', flexGrow: 1 }}>
        <Box component="main" sx={{ flexGrow: 1, p: 0, overflow: 'auto' }}>
          <Toolbar />
          <UserList />
        </Box>
      </Box>
    </Box>
  );
};

export default Users;