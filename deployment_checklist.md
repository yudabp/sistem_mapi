# Deployment Checklist - New Features

## Pre-Deployment Checklist

### 1. Code Review ✅
- [ ] All new code has been reviewed
- [ ] Code follows Laravel and PHP best practices
- [ ] No security vulnerabilities identified
- [ ] Performance impact assessed

### 2. Database Readiness ✅
- [ ] Database migrations are reversible
- [ ] No breaking changes to existing schema
- [ ] Foreign key constraints properly defined
- [ ] Indexes added for performance

### 3. Testing ✅
- [ ] Unit tests passing
- [ ] Feature tests passing
- [ ] Integration tests passing
- [ ] User acceptance testing completed

### 4. Documentation ✅
- [ ] Technical documentation updated
- [ ] User documentation created
- [ ] API documentation updated (if applicable)
- [ ] Deployment guide prepared

## Deployment Steps

### Phase 1: Preparation
- [ ] Create backup of current database
- [ ] Create backup of current codebase
- [ ] Schedule maintenance window
- [ ] Notify stakeholders of deployment

### Phase 2: Database Deployment
- [ ] Run database migrations:
  ```bash
  php artisan migrate
  ```
- [ ] Verify migration success
- [ ] Check database integrity
- [ ] Test rollback procedure

### Phase 3: Code Deployment
- [ ] Deploy new code to production
- [ ] Install new dependencies:
  ```bash
  composer install --optimize-autoloader --no-dev
  ```
- [ ] Compile assets:
  ```bash
  npm run build
  ```
- [ ] Clear caches:
  ```bash
  php artisan cache:clear
  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  ```

### Phase 4: Post-Deployment
- [ ] Verify all services running
- [ ] Test new features in production environment
- [ ] Monitor error logs
- [ ] Check performance metrics

## Feature-Specific Testing Checklist

### 1. Data Penjualan Enhancement
- [ ] SP Number dropdown shows production data
- [ ] SP Numbers already sold are not available
- [ ] TBS and KG quantities auto-fill correctly
- [ ] Fields are properly disabled when SP Number selected
- [ ] Total Amount calculates automatically
- [ ] Form validation works correctly
- [ ] Photo viewing works for sales records

### 2. Photo Modal Implementation
- [ ] Photo modal opens in all modules:
  - [ ] Data Produksi
  - [ ] Data Penjualan
  - [ ] Keuangan Perusahaan
  - [ ] Buku Kas
  - [ ] Data Hutang
- [ ] Modal displays correctly on mobile and desktop
- [ ] Zoom functionality works
- [ ] Keyboard navigation works (ESC to close)
- [ ] Loading states display correctly
- [ ] Photo URLs are secure and accessible

### 3. Keuangan Perusahaan Enhancement
- [ ] Only expense type available (no income selection)
- [ ] Form submits successfully
- [ ] Corresponding income entry created in Buku Kas
- [ ] Visual indicator shows auto-creation
- [ ] Data consistency maintained between modules
- [ ] Photo viewing works for financial records

### 4. Buku Kas Enhancement
- [ ] Debt payment option available in expense type
- [ ] Unpaid debts load correctly in dropdown
- [ ] Debt selection works properly
- [ ] Purpose field auto-fills for debt payments
- [ ] Debt status updates to "lunas" when paid
- [ ] Paid date is set correctly
- [ ] Unpaid debts list updates after payment
- [ ] Photo viewing works for cash book records

## Rollback Plan

### Immediate Rollback Triggers
- [ ] Critical errors in new features
- [ ] Performance degradation > 50%
- [ ] Data corruption or loss
- [ ] Security vulnerabilities discovered

### Rollback Steps
1. **Code Rollback**:
   ```bash
   git checkout previous_version
   composer install --optimize-autoloader --no-dev
   npm run build
   ```

2. **Database Rollback**:
   ```bash
   php artisan migrate:rollback --step=X
   # Where X is number of new migrations
   ```

3. **Cache Clear**:
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

## Monitoring Plan

### 1. Performance Monitoring
- [ ] Monitor page load times
- [ ] Track database query performance
- [ ] Monitor memory usage
- [ ] Check response times for new features

### 2. Error Monitoring
- [ ] Set up error tracking for new components
- [ ] Monitor JavaScript errors in browser console
- [ ] Track Laravel log files
- [ ] Monitor database connection errors

### 3. User Experience Monitoring
- [ ] Collect user feedback on new features
- [ ] Monitor feature usage statistics
- [ ] Track user success rates for new workflows
- [ ] Monitor support ticket volume

## Post-Deployment Tasks

### 1. Verification
- [ ] All stakeholders confirm features working
- [ ] No regression in existing functionality
- [ ] Performance metrics within acceptable range
- [ ] Security scan completed successfully

### 2. Documentation
- [ ] Update internal documentation with any changes
- [ ] Archive deployment notes
- [ ] Update user guides if needed
- [ ] Document any lessons learned

### 3. Optimization
- [ ] Analyze performance metrics
- [ ] Optimize any slow queries identified
- [ ] Fine-tune caching strategy
- [ ] Plan for next iteration

## Contact Information

### Deployment Team
- **Lead Developer**: [Name]
- **Database Administrator**: [Name]
- **DevOps Engineer**: [Name]
- **QA Engineer**: [Name]

### Stakeholders
- **Product Owner**: [Name]
- **Business Users**: [Names]
- **Support Team**: [Names]

### Emergency Contacts
- **Critical Issues**: [Phone/Email]
- **Database Issues**: [Phone/Email]
- **Infrastructure Issues**: [Phone/Email]

---

## Sign-off

### Pre-Deployment
- **Development Lead**: _________________________ Date: _______
- **QA Lead**: _________________________ Date: _______
- **Database Admin**: _________________________ Date: _______

### Post-Deployment
- **Deployment Lead**: _________________________ Date: _______
- **Stakeholder**: _________________________ Date: _______
- **Support Lead**: _________________________ Date: _______