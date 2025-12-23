import React from 'react';
import { Container, Typography, Grid, Card, CardContent, Button } from '@mui/material';
import { Link } from 'react-router-dom';

const Dashboard = () => {
  return (
    <Container maxWidth="lg" sx={{ mt: 4, mb: 4 }}>
      <Typography variant="h3" gutterBottom>
        Dashboard
      </Typography>
      <Grid container spacing={3}>
        <Grid item xs={12} sm={6} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Users
              </Typography>
              <Typography variant="h5" component="h2">
                10
              </Typography>
              <Button component={Link} to="/users" size="small">
                View
              </Button>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} sm={6} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Companies
              </Typography>
              <Typography variant="h5" component="h2">
                5
              </Typography>
              <Button component={Link} to="/companies" size="small">
                View
              </Button>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} sm={6} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Stores
              </Typography>
              <Typography variant="h5" component="h2">
                8
              </Typography>
              <Button component={Link} to="/stores" size="small">
                View
              </Button>
            </CardContent>
          </Card>
        </Grid>
        <Grid item xs={12} sm={6} md={3}>
          <Card>
            <CardContent>
              <Typography color="textSecondary" gutterBottom>
                Products
              </Typography>
              <Typography variant="h5" component="h2">
                50
              </Typography>
              <Button component={Link} to="/products" size="small">
                View
              </Button>
            </CardContent>
          </Card>
        </Grid>
      </Grid>
    </Container>
  );
};

export default Dashboard;