import '../constants/api_constants.dart';

String resolveImageUrl(String path) {
  if (path.isEmpty) return '';
  if (path.startsWith('http')) return path;
  if (path.startsWith('assets/')) return path;
  
  // Remove leading slash if present
  final cleanPath = path.startsWith('/') ? path.substring(1) : path;
  
  // If path already starts with storage/, don't add it again
  if (cleanPath.startsWith('storage/')) {
    return '${ApiConstants.baseUrl}/$cleanPath';
  }
  
  // Default to storage folder for relative paths
  return '${ApiConstants.baseUrl}/storage/$cleanPath';
}
