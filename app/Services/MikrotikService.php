<?php

namespace App\Services;

use App\Models\MikrotikDevice;
use RouterOS\Client;
use RouterOS\Query;

class MikrotikService
{
    private Client $client;
    private MikrotikDevice $device;

    public function __construct(MikrotikDevice $device)
    {
        $this->device = $device;
        $this->client = new Client([
            'host' => $device->host,
            'user' => $device->username,
            'pass' => $device->password,
            'port' => $device->port,
        ]);
    }

    // Get semua user hotspot aktif
    public function getActiveUsers(): array
    {
        $query = new Query('/ip/hotspot/active/print');
        return $this->client->query($query)->read();
    }

    // Tambah user hotspot
    public function addUser(string $username, string $password, string $profile): array
    {
        $query = (new Query('/ip/hotspot/user/add'))
            ->equal('name', $username)
            ->equal('password', $password)
            ->equal('profile', $profile);
        return $this->client->query($query)->read();
    }

    // Hapus user hotspot
    public function removeUser(string $username): array
    {
        $query = new Query('/ip/hotspot/user/print');
        $query->where('name', $username);
        $users = $this->client->query($query)->read();

        if (!empty($users)) {
            $id = $users[0]['.id'];
            $query = (new Query('/ip/hotspot/user/remove'))
                ->equal('.id', $id);
            return $this->client->query($query)->read();
        }
        return [];
    }

    // Enable user
    public function enableUser(string $username): void
    {
        $query = new Query('/ip/hotspot/user/print');
        $query->where('name', $username);
        $users = $this->client->query($query)->read();

        if (!empty($users)) {
            $id = $users[0]['.id'];
            $query = (new Query('/ip/hotspot/user/enable'))
                ->equal('.id', $id);
            $this->client->query($query)->read();
        }
    }

    // Disable user
    public function disableUser(string $username): void
    {
        $query = new Query('/ip/hotspot/user/print');
        $query->where('name', $username);
        $users = $this->client->query($query)->read();

        if (!empty($users)) {
            $id = $users[0]['.id'];
            $query = (new Query('/ip/hotspot/user/disable'))
                ->equal('.id', $id);
            $this->client->query($query)->read();
        }
    }

    // Disconnect user aktif
    public function disconnectUser(string $username): void
    {
        $query = new Query('/ip/hotspot/active/print');
        $query->where('user', $username);
        $actives = $this->client->query($query)->read();

        if (!empty($actives)) {
            $id = $actives[0]['.id'];
            $query = (new Query('/ip/hotspot/active/remove'))
                ->equal('.id', $id);
            $this->client->query($query)->read();
        }
    }

    // Get semua profile hotspot
    public function getProfiles(): array
    {
        $query = new Query('/ip/hotspot/user/profile/print');
        return $this->client->query($query)->read();
    }

    // Buat profile hotspot
    public function addProfile(string $name, string $rateLimit, int $sharedUsers = 1): void
    {
        // Cek apakah profile sudah ada
        $query = new Query('/ip/hotspot/user/profile/print');
        $query->where('name', $name);
        $existing = $this->client->query($query)->read();

        if (!empty($existing)) {
            // Update profile yang sudah ada
            $id = $existing[0]['.id'];
            $query = (new Query('/ip/hotspot/user/profile/set'))
                ->equal('.id', $id)
                ->equal('rate-limit', $rateLimit)
                ->equal('shared-users', $sharedUsers);
            $this->client->query($query)->read();
        } else {
            // Buat profile baru
            $query = (new Query('/ip/hotspot/user/profile/add'))
                ->equal('name', $name)
                ->equal('rate-limit', $rateLimit)
                ->equal('shared-users', $sharedUsers);
            $this->client->query($query)->read();
        }
    }

    // Hapus profile hotspot
    public function removeProfile(string $name): void
    {
        $query = new Query('/ip/hotspot/user/profile/print');
        $query->where('name', $name);
        $profiles = $this->client->query($query)->read();

        if (!empty($profiles)) {
            $id = $profiles[0]['.id'];
            $query = (new Query('/ip/hotspot/user/profile/remove'))
                ->equal('.id', $id);
            $this->client->query($query)->read();
        }
    }

    // Get identity router
    public function getIdentity(): string
    {
        $query = new Query('/system/identity/print');
        $result = $this->client->query($query)->read();
        return $result[0]['name'] ?? 'Unknown';
    }

    // Get resource info (CPU, RAM, uptime)
    public function getResource(): array
    {
        $query = new Query('/system/resource/print');
        $result = $this->client->query($query)->read();
        return $result[0] ?? [];
    }

    public function loginTrial(string $ip, string $mac): void
    {
        // Hapus user trial lama jika ada
        $query = new Query('/ip/hotspot/active/print');
        $query->where('mac-address', $mac);
        $active = $this->client->query($query)->read();

        if (empty($active)) {
            // Login user sebagai trial
            $query = (new Query('/ip/hotspot/user/add'))
                ->equal('name', 'trial_' . str_replace(':', '', $mac))
                ->equal('password', '')
                ->equal('profile', 'free-trial')
                ->equal('mac-address', $mac)
                ->equal('limit-uptime', '10m');
            $this->client->query($query)->read();
        }
    }

    public function activateTrialByMac(string $mac): void
    {
        // Cek apakah sudah active
        $query = new Query('/ip/hotspot/active/print');
        $query->where('mac-address', $mac);
        $active = $this->client->query($query)->read();

        if (!empty($active)) return; // Sudah active

        // Cek host
        $query = new Query('/ip/hotspot/host/print');
        $query->where('mac-address', $mac);
        $hosts = $this->client->query($query)->read();

        if (empty($hosts)) return;

        $hostId = $hosts[0]['.id'];

        // Set host sebagai authorized dengan profile free-trial
        $query = (new Query('/ip/hotspot/host/set'))
            ->equal('.id', $hostId)
            ->equal('to-address', $hosts[0]['address'])
            ->equal('authorized', 'true');
        $this->client->query($query)->read();
    }

    public function loginUser(string $username, string $password, string $ip, string $mac): void
    {
        $query = (new Query('/ip/hotspot/active/login'))
            ->equal('user', $username)
            ->equal('password', $password)
            ->equal('ip', $ip)
            ->equal('mac-address', $mac);
        $this->client->query($query)->read();
    }

    public function changeUserProfile(string $username, string $profile): void
    {
        // Update profile di user list saja, TANPA hapus session aktif
        $query = new Query('/ip/hotspot/user/print');
        $query->where('name', $username);
        $users = $this->client->query($query)->read();

        if (!empty($users)) {
            $id = $users[0]['.id'];
            $query = (new Query('/ip/hotspot/user/set'))
                ->equal('.id', $id)
                ->equal('profile', $profile);
            $this->client->query($query)->read();
        }
    }
}
