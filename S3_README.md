# Bagisto S3 Configuration Guide

This guide explains how to configure Amazon S3 storage for your Bagisto installation.

## Prerequisites

1. AWS Account with S3 access
2. S3 bucket created
3. AWS Access Key and Secret Key
4. AWS CLI installed (optional, for maintenance)

## Configuration Steps

### 1. Environment Configuration

Add the following to your `.env` file:

```env
FILESYSTEM_DISK=public
FILESYSTEM_CLOUD=s3

AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=your_region
AWS_BUCKET=your_bucket_name
AWS_URL=https://your-bucket-name.s3.your-region.amazonaws.com
```

### 2. S3 Bucket Configuration

1. Create an S3 bucket in your AWS Console
2. Configure bucket settings:
   - Uncheck "Block all public access" (for development)
   - Set "Object Ownership" to "Bucket owner enforced"
   - Add bucket policy for public read access:
   ```json
   {
       "Version": "2012-10-17",
       "Statement": [
           {
               "Sid": "PublicReadGetObject",
               "Effect": "Allow",
               "Principal": "*",
               "Action": "s3:GetObject",
               "Resource": "arn:aws:s3:::your-bucket-name/*"
           }
       ]
   }
   ```

### 3. Filesystem Configuration

The `config/filesystems.php` file should have the following S3 configuration:

```php
's3' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
    'url' => env('AWS_URL'),
    'visibility' => 'public',
],
```

### 4. Directory Structure

S3 will maintain the following directory structure:
- `/product/` - For product images
- `/cache/` - For cached images
- `/theme/` - For theme assets

### 5. Testing Configuration

1. Clear Laravel cache:
```bash
php artisan optimize:clear
```

2. Test upload through admin panel:
- Log into admin panel
- Create/edit a product
- Upload an image
- Verify the image is accessible via S3 URL

### 6. AWS CLI Commands (Optional)

Useful AWS CLI commands for maintenance:

```bash
# Sync local storage to S3
aws s3 sync storage/app/public s3://your-bucket-name

# List bucket contents
aws s3 ls s3://your-bucket-name

# Remove all files from bucket
aws s3 rm s3://your-bucket-name --recursive
```

## Troubleshooting

1. Image Access Denied
   - Verify bucket policy is configured correctly
   - Check that "Block all public access" is disabled
   - Ensure Object Ownership is set to "Bucket owner enforced"

2. Upload Failures
   - Verify AWS credentials are correct
   - Check PHP has sufficient memory for uploads
   - Verify proper permissions on storage directories

3. Missing Images
   - Clear Laravel cache
   - Verify correct S3 URL configuration
   - Check image paths in database

## Security Considerations

For production environments:
1. Use IAM roles with minimal required permissions
2. Enable bucket logging
3. Configure CORS if needed
4. Consider using CloudFront for content delivery
5. Implement proper bucket policies based on your security requirements

## Support

For additional support:
- Bagisto Forums: https://forums.bagisto.com/
- AWS S3 Documentation: https://docs.aws.amazon.com/s3/ 