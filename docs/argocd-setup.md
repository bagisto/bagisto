# Argo CD và GitOps cho Bagisto

## 1. Kiến trúc CI/CD hiện tại

Hệ thống gồm bốn lớp:

1. Source repository `https://github.com/CuongMotNang/bagisto` chứa mã nguồn Bagisto và GitHub Actions workflow.
2. GitHub Actions build, test, scan, sign, push Docker image lên GHCR, sau đó cập nhật image tag trong GitOps repository.
3. GitOps repository `https://github.com/CuongMotNang/bagisto-gitops` là nguồn trạng thái mong muốn duy nhất cho Kubernetes.
4. Argo CD chạy trong cluster, theo dõi GitOps repository và tự động đồng bộ namespace `bagisto`.

Luồng triển khai:

```text
Source commit
  -> GitHub Actions: build + test + scan + sign + push GHCR
  -> GitHub Actions: update image tag trong bagisto-gitops
  -> GitOps commit trên branch master
  -> Argo CD phát hiện revision mới
  -> Argo CD render overlays/dev bằng Kustomize
  -> Argo CD apply manifest vào namespace bagisto
  -> Deployment rolling update, image mới được pull từ GHCR
```

GitHub Actions không chạy `kubectl apply`, không chạy `helm upgrade` và không deploy trực tiếp vào Kubernetes.

## 2. Thông tin cluster

| Thành phần | Giá trị |
|---|---|
| Kubernetes context | `minikube` |
| Distribution | Minikube |
| Kubernetes server | `v1.37.0` |
| Node | `minikube`, control-plane, Ready |
| CNI | kindnet |
| StorageClass mặc định | `standard` (minikube-hostpath) |
| Ingress Controller | Không có |
| Bagisto namespace | `bagisto` |
| Argo CD namespace | `argocd` |

## 3. Thành phần Argo CD

Argo CD được cài bằng manifest non-HA chính thức của phiên bản `v3.6.1`:

```bash
kubectl create namespace argocd
kubectl apply -n argocd --server-side --force-conflicts \
  -f https://raw.githubusercontent.com/argoproj/argo-cd/v3.6.1/manifests/install.yaml
```

Các thành phần chính:

| Thành phần | Chức năng |
|---|---|
| `argocd-server` | API server và giao diện web, nhận lệnh từ UI/CLI |
| `argocd-application-controller` | So sánh Git với cluster và thực hiện sync |
| `argocd-repo-server` | Clone repository, render Kustomize/Helm |
| `argocd-redis` | Cache trạng thái cho controller và server |
| `argocd-dex-server` | SSO/Dex, hiện chưa cấu hình SSO |
| `argocd-applicationset-controller` | Quản lý ApplicationSet, hiện chưa dùng |
| `argocd-notifications-controller` | Gửi thông báo, hiện chưa cấu hình |

Tất cả Pod nằm trong namespace `argocd` và đã ở trạng thái Running/Ready.

## 4. Application đang quản lý

| Thuộc tính | Giá trị |
|---|---|
| Application | `argocd/bagisto` |
| AppProject | `bagisto` |
| Repository | `https://github.com/CuongMotNang/bagisto-gitops` |
| Branch | `master` |
| Path | `overlays/dev` |
| Renderer | Kustomize |
| Destination cluster | `https://kubernetes.default.svc` |
| Destination namespace | `bagisto` |
| Automated sync | Bật |
| Self-heal | Bật |
| Prune | Tắt |

Ghi chú: GitOps repository hiện không có branch `main`. Branch mặc định thực tế là `master`. Vì vậy Application đang theo dõi `master`. Nếu chuyển sang `main`, cần tạo/đổi default branch, cập nhật trigger của GitOps CI và đặt biến `GITOPS_BRANCH` cho application CI.

`AppProject` giới hạn:

- Chỉ cho phép source repository `https://github.com/CuongMotNang/bagisto-gitops`.
- Chỉ cho phép deploy vào cluster hiện tại và namespace `bagisto`.
- Chỉ cho phép các loại tài nguyên đang dùng: Namespace, ConfigMap, Service, PersistentVolumeClaim, Deployment, StatefulSet.

Prune đang tắt vì đây là lần đầu Argo CD tiếp nhận các tài nguyên được tạo trước đó bằng `kubectl`. Tắt prune giúp tránh xóa nhầm tài nguyên ngoài Git. Chỉ bật prune sau khi đã kiểm tra inventory và xác nhận không có tài nguyên quan trọng nằm ngoài Git.

## 5. Truy cập Argo CD

Argo CD không được expose bằng LoadBalancer hoặc Ingress. Dịch vụ `argocd-server` là `ClusterIP`.

Port-forward giao diện web:

```bash
kubectl -n argocd port-forward svc/argocd-server 8080:443
```

Mở `https://localhost:8080`. Chứng chỉ hiện là self-signed nên trình duyệt sẽ cảnh báo.

Tên đăng nhập mặc định là `admin`. Lấy mật khẩu khởi tạo mà không ghi mật khẩu vào tài liệu:

```bash
kubectl -n argocd get secret argocd-initial-admin-secret \
  -o jsonpath='{.data.password}' | base64 -d
```

Sau khi đổi mật khẩu admin, nên xóa secret khởi tạo:

```bash
kubectl -n argocd delete secret argocd-initial-admin-secret
```

Đăng nhập bằng CLI:

```bash
argocd login localhost:8080 --username admin --insecure
```

Hoặc dùng port-forward tự động của CLI:

```bash
argocd login localhost:8080 \
  --username admin \
  --insecure \
  --port-forward \
  --port-forward-namespace argocd
```

## 6. Lệnh kubectl và argocd hữu ích

```bash
# Trạng thái Application
kubectl -n argocd get application bagisto
kubectl -n argocd get application bagisto -o yaml

# Trạng thái Pod của Argo CD
kubectl -n argocd get pods
kubectl -n argocd logs deploy/argocd-server --tail=100
kubectl -n argocd logs deploy/argocd-repo-server --tail=100
kubectl -n argocd logs statefulset/argocd-application-controller --tail=100

# Trạng thái Bagisto
kubectl -n bagisto get deploy,sts,pod,svc,pvc
kubectl -n bagisto get events --sort-by=.lastTimestamp

# CLI Argo CD
argocd app list
argocd app get bagisto
argocd app diff bagisto
argocd app history bagisto
argocd app sync bagisto
argocd app refresh bagisto --hard-refresh
```

## 7. Ý nghĩa Sync Status và Health Status

`Sync Status` cho biết cluster có khớp với manifest trong Git hay không:

| Trạng thái | Ý nghĩa |
|---|---|
| `Synced` | Tài nguyên trong cluster khớp với trạng thái khai báo trong Git |
| `OutOfSync` | Cluster khác với Git, hoặc Argo CD chưa áp dụng revision mới |
| `Unknown` | Argo CD chưa đủ dữ liệu để so sánh |

`Health Status` cho biết tài nguyên có đang hoạt động tốt theo health assessment của Argo CD hay không:

| Trạng thái | Ý nghĩa |
|---|---|
| `Healthy` | Tài nguyên hoạt động bình thường |
| `Progressing` | Đang rollout, đang chờ Pod hoặc tài nguyên chưa sẵn sàng |
| `Degraded` | Tài nguyên có lỗi, ví dụ Pod CrashLoopBackOff hoặc Deployment không đủ replica |
| `Missing` | Tài nguyên được khai báo trong Git nhưng chưa có trong cluster |
| `Suspended` | Tài nguyên đang bị tạm dừng, ví dụ Deployment scale 0 có chủ đích |

Một Application có thể `Synced` nhưng `Degraded`. Ví dụ manifest đã được apply đúng, nhưng image không pull được hoặc container crash.

## 8. Xử lý lỗi thường gặp

### Repository không kết nối được

Kiểm tra:

```bash
argocd app get bagisto
kubectl -n argocd logs deploy/argocd-repo-server --tail=200
kubectl -n argocd get application bagisto -o json | jq '.status.conditions'
```

Nguyên nhân thường gặp:

- Repository private nhưng chưa cấu hình credential.
- Sai URL hoặc sai branch/path.
- Repo server không có DNS/egress ra GitHub.
- Repository credential hết hạn.

Repository `bagisto-gitops` hiện là public nên không cần credential đọc Git. Nếu chuyển sang private, nên dùng deploy key chỉ đọc hoặc GitHub App/PAT giới hạn quyền đọc.

### OutOfSync

```bash
argocd app diff bagisto
argocd app get bagisto
```

Nếu thay đổi hợp lệ trong Git, chờ auto-sync hoặc chạy:

```bash
argocd app refresh bagisto --hard-refresh
argocd app sync bagisto
```

Nếu cluster bị sửa tay, bật self-heal sẽ tự đưa về trạng thái trong Git. Không sửa trực tiếp bằng `kubectl` cho các tài nguyên Argo CD đang quản lý.

### Degraded

```bash
kubectl -n bagisto get pods
kubectl -n bagisto describe pod <pod>
kubectl -n bagisto logs <pod> --all-containers --tail=200
kubectl -n bagisto get events --sort-by=.lastTimestamp
```

### ImagePullBackOff

Kiểm tra tên image và tag trong GitOps repository:

```bash
kubectl -n bagisto describe pod <pod>
```

Các nguyên nhân thường gặp:

- Image hoặc tag không tồn tại trên GHCR.
- Package GHCR private nhưng namespace `bagisto` chưa có `imagePullSecret`.
- Sai registry hoặc sai owner.

Credential đọc Git repository và credential pull image GHCR là hai cơ chế khác nhau. Repo public không có nghĩa image cũng public, và ngược lại.

Nếu image private, tạo pull secret trong namespace `bagisto` và tham chiếu trong Pod spec. Không commit secret vào Git.

### CrashLoopBackOff

```bash
kubectl -n bagisto logs <pod> --all-containers --previous
kubectl -n bagisto describe pod <pod>
```

Kiểm tra biến môi trường, Secret `bagisto-app-secret`, kết nối MySQL/Redis, quyền ghi volume uploads và entrypoint của image.

## 9. Rollback

Cách ưu tiên là revert commit trong GitOps repository:

```bash
git clone https://github.com/CuongMotNang/bagisto-gitops.git
cd bagisto-gitops
git revert <commit-sha>
git push origin master
```

Sau khi push, Argo CD sẽ phát hiện revision mới và tự động sync. GitOps repository vẫn là nguồn trạng thái mong muốn duy nhất.

Có thể dùng lịch sử Argo CD để tham khảo:

```bash
argocd app history bagisto
argocd app rollback bagisto <history-id>
```

Tuy nhiên rollback trực tiếp bằng Argo CD chỉ là biện pháp tạm thời. Nếu Git chưa được revert, lần sync sau có thể đưa cấu hình cũ trở lại hoặc gây OutOfSync.

## 10. Ghi chú kiểm tra thực tế

Đã thực hiện:

- Cài Argo CD `v3.6.1` non-HA, tất cả Pod Ready.
- Tạo `AppProject/bagisto` và `Application/bagisto`.
- Sync lần đầu thành công, image chuyển từ `sha-7f040f0` sang `sha-d77394a`.
- Xác minh Pod pull image `ghcr.io/cuongmotnang/bagisto-app@sha256:70176143594798896f18d8111fe46403ac7fdd7c3159c146e79b8c41260cb74b` và `ghcr.io/cuongmotnang/bagisto-web@sha256:11529a954768a4a6a59a0242e00569adea1b80b1771245760a5fce79f9b634d5`.
- Xác minh web trả HTTP 302 qua port-forward.
- Kiểm tra self-heal bằng cách sửa `APP_DEBUG` trong ConfigMap từ `false` sang `true`; Argo CD phát hiện OutOfSync và đưa về `false`.
- Kiểm tra luồng GitOps bằng commit `3c16a66` đổi image sang `sha-7f040f0`, xác minh Argo CD sync và rollout thành công.
- Khôi phục bằng commit `ac630d9` về `sha-d77394a`, xác minh Application `Synced/Healthy`.
- GitOps CI đã trigger và chạy thành công cho cả hai commit `3c16a66` và `ac630d9`.
- Trạng thái cuối: Application `Synced/Healthy`, các Pod Bagisto Running, web trả HTTP 302 qua port-forward.
- `argocd app diff bagisto` không trả về khác biệt giữa cluster và manifest render từ `overlays/dev`.

Lưu ý: drift chỉ là annotation thêm vào metadata không được Argo CD đánh giá là OutOfSync trong cấu hình hiện tại. Drift trên field được quản lý như `ConfigMap.data` được phát hiện và self-heal bình thường.

## 11. Bảo mật và việc cần làm trước production

Đã áp dụng:

- Argo CD nằm trong namespace riêng `argocd`.
- `argocd-server` chỉ là `ClusterIP`, không có Ingress hoặc LoadBalancer.
- Không lưu credential Git trong repository.
- Repository GitOps là public nên không cấu hình repo credential.
- AppProject giới hạn source repo, destination namespace và loại tài nguyên.
- AppProject không whitelist `Secret`, nên Argo CD không quản lý hoặc vô tình đưa Secret lên UI. Secret `bagisto-app-secret` và `bagisto-secrets` được tạo tay trong cluster.
- Prune đang tắt.
- Không expose Argo CD ra Internet.

Cần làm trước production:

- Đổi mật khẩu admin mặc định và xóa secret `argocd-initial-admin-secret` sau khi đổi.
- Bật SSO và cấu hình RBAC theo nhóm/role thay vì dùng tài khoản admin chung.
- Không expose UI trực tiếp. Nếu cần truy cập ngoài cluster, dùng Ingress nội bộ kèm TLS, SSO và giới hạn IP.
- Thay chứng chỉ self-signed bằng certificate do tổ chức cấp.
- Thu hẹp quyền của `argocd-application-controller`. Bản cài tiêu chuẩn dùng ClusterRole rộng; production nên dùng AppProject/namespace-scoped install hoặc ClusterRole tối thiểu.
- Bật prune sau khi kiểm tra inventory và có quy trình backup.
- Cấu hình credential đọc GitOps repository nếu chuyển sang private, bằng deploy key chỉ đọc hoặc GitHub App.
- Cấu hình `imagePullSecret` trong namespace `bagisto` nếu GHCR package chuyển sang private.
- Cân nhắc dùng HA install, nhiều replica và PodDisruptionBudget cho production.
- Bật cảnh báo lỗi sync/health qua notifications controller.
- Thiết lập backup cho GitOps repository và tài nguyên có trạng thái như PVC.
- Thắt chặt branch protection của GitOps repository. Hiện tại push trực tiếp lên `master` có thể bypass rule "Changes must be made through a pull request"; production nên yêu cầu pull request và review.
- Argo CD sync ngay khi thấy commit mới trên branch, không chờ GitOps CI success. Cần bắt buộc required status checks và merge qua pull request để validation chạy trước khi Argo CD deploy.
- Cẩn thận với finalizer `resources-finalizer.argocd.argoproj.io` trên Application `bagisto`. Xóa Application sẽ xóa toàn bộ tài nguyên Argo CD đang quản lý, bao gồm PVC và Namespace. Nếu cần giữ dữ liệu, bỏ finalizer hoặc xử lý cascade trước khi xóa.
- Thống nhất branch `master` hay `main` giữa GitOps CI, application CI và Argo CD.
- Cân nhắc xác minh chữ ký Cosign trước khi deploy bằng policy engine; Argo CD mặc định không kiểm tra chữ ký image.
